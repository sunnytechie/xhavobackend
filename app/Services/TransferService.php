<?php

namespace App\Services;

use App\Models\Stash;
use App\Models\Withdrawal;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Nette\Utils\Random;

class TransferService
{
    public function makeTransfer($withdrawal_id, $user_id, $userbank)
    {
        //Find withdrawal
        $withdrawal = Withdrawal::find($withdrawal_id);

        //Get User Stash account
        $stash = Stash::where('user_id', $user_id)->first();


        $client = new Client();
        $url = "https://api.flutterwave.com/v3/transfers";

        $params = [
            "account_bank" => $userbank->bank_code,
            "account_number" => $userbank->account_number,
            "amount" => $withdrawal->amount,
            "narration" => "Withrawal Request to" . ' ' . $userbank->account_number,
            "currency" => "NGN",
            "reference" => now() . $userbank->account_number . "_tx_ref_XHAVO_" . rand(0000, 9999),
            "callback_url" => "https://www.flutterwave.com/ng/",
            "debit_currency" => "NGN"
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLUTTERWAVE_SECRET_KEY'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $params,
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['status']) && $data['status'] === 'success') {
                // Transfer is successfully queued

                $withdrawal->status = 'queued';
                $withdrawal->save();

                //update stash
                $stash->amount = $stash->amount - $withdrawal->amount;
                $stash->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Transfer Processing',
                    //'data' => $data['data'],
                ]);
            } else {
                // Transfer failed
                return response()->json([
                    'status' => false,
                    'message' => $data['message'] ?? 'Transfer failed',
                    //'data' => $data['data'] ?? [],
                ]);
            }


        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error',
            ]);
        }
    }
}
