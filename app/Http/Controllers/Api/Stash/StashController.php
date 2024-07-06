<?php

namespace App\Http\Controllers\Api\Stash;

use App\Models\Fund;
use App\Models\User;
use App\Models\Stash;
use App\Models\Stashhistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\TransferService;
use App\Services\VerifyTxRefService;

class StashController extends Controller
{

    protected $transferToLocalBank, $verifyTxRefService;

    public function __construct
        (
        TransferService $transferToLocalBank,
        VerifyTxRefService $verifyTxRefService
        )
    {
        $this->transferToLocalBank = $transferToLocalBank;
        $this->verifyTxRefService = $verifyTxRefService;
    }

    public function index($user_id)
    {
        //get user
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $stash = $user->stash;
        if (!$stash) {
            //create stash
            $stash = new Stash();
            $stash->user_id = $user->id;
            $stash->amount = 0;
        }

        //user stashhistory
        $stashHistory = $user->stashhistories;

        return response()->json([
            'status' => true,
            'message' => 'Stash retrieved successfully',
            'data' => [
                'stash' => $stash,
                'stashHistories' => $stashHistory
            ]
        ], 200);
    }

    public function store(Request $request, $user_id)
    {
        //get user
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        //validate request
        $request->validate([
            'amount' => 'required|numeric',
            'tx_ref' => 'required|string',
            'tx_currency' => 'required|string'
        ]);

        //get stash
        $stash = $user->stash;
        if (!$stash) {
            //create stash
            $stash = new Stash();
            $stash->user_id = $user->id;
            $stash->amount = 0;
        }

        //update stash
        $stash->amount = $stash->amount + $request->amount;
        $stash->save();

        //create fund
        $fund = new Fund();
        $fund->user_id = $user->id;
        $fund->amount = $request->amount;
        $fund->tx_ref = $request->tx_ref;
        $fund->tx_currency = $request->tx_currency;
        $fund->save();

        //create stash history
        $stashHistory = new Stashhistory();
        $stashHistory->user_id = $user->id;
        $stashHistory->amount = $request->amount;
        $stashHistory->title = 'Stash funding';
        $stashHistory->type = 'fund';
        $stashHistory->status = 'completed';
        $stashHistory->save();

        return response()->json([
            'status' => true,
            'message' => 'Funded successfully',
            'data' => [
                'fund' => $fund,
                'stash' => $stash,
            ]
        ], 200);
    }

    //Paystack #no used yet
    public function verifyPayment($reference)
    {
        $secretKey = 'YOUR_SECRET_KEY'; // Replace with your actual Paystack secret key

        $url = "https://api.paystack.co/transaction/verify/{$reference}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Cache-Control' => 'no-cache',
            ])->get($url);

            // Get the response body as an array
            $responseData = $response->json();

            // You can now work with $responseData to handle the verification result
            dd($responseData);
        } catch (\Exception $e) {
            // Handle request error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function topUp(Request $request) {
        // Retrieve the webhook event data
        //$event = $request->input('event');

        $payload = json_decode($request->getContent());

        try {
            // charge.completed
            if ($payload->event === 'charge.completed') {
                $amount = $payload->data->amount;
                $email = $payload->data->customer->email;
                $tx_ref = $payload->data->tx_ref;
                $currency = $payload->data->currency;


                //Verify Transaction
                $verify = $this->verifyTxRefService->verifyTransaction($tx_ref);
                if ($verify == false) {
                    return;
                }


                $user = User::where('email', $email)->first();

                if ($user) {

                    $this->topUpStash($user->id, $amount, $tx_ref, $currency);

                    return response()->json([
                        'status'=> true,
                        'message' => 'Success'
                    ], 200);
                }

            }

        } catch (\Throwable $th) {

            // If the event is not recognized, respond with a 400 status code
            return response()->json([
                'status'=> false,
                'message' => 'Failed'
            ], 400);

        }
    }


    private function topUpStash($id, $amount, $tx_ref, $currency) {
        $stash = Stash::where('user_id', $id)->lockForUpdate()->first();

        if (!$stash) {
            $stash = new Stash();
            $stash->user_id = $id;
            $stash->save();
        }

        $checkDuplicates = Stashhistory::where('tx_ref', $tx_ref)
                        ->where('status', 'completed')
                        ->first();
        if ($checkDuplicates) {
            return;
        }

        $stash->amount += $amount;
        $stash->save();

        //Stash history
        $fund = new Fund();
        $fund->user_id = $id;
        $fund->amount = $amount;
        $fund->tx_ref = $tx_ref;
        $fund->tx_currency = $currency;
        $fund->save();

        //create stash history
        $stashHistory = new Stashhistory();
        $stashHistory->user_id = $id;
        $stashHistory->amount = $amount;
        $stashHistory->title = 'Stash funding';
        $stashHistory->type = 'fund';
        $stashHistory->status = 'completed';
        $stashHistory->tx_ref = $tx_ref;
        $stashHistory->save();

        return;
    }
}
