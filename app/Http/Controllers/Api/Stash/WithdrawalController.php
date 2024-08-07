<?php

namespace App\Http\Controllers\Api\Stash;

use App\Models\Otp;
use App\Models\User;
use App\Models\Stashhistory;
use Illuminate\Http\Request;
use App\Mail\WithdrawalPinMail;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Mail;
use App\Services\TransferService;
use App\Services\VerifyTxRefService;

class WithdrawalController extends Controller
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

    public function store($user_id, Request $request)
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
        ]);

        //get user stash
        $stash = $user->stash;
        if (!$stash) {
            return response()->json([
                'status' => false,
                'message' => 'Stash not found'
            ], 404);
        }

        //check if user has enough balance
        if ($stash->amount < $request->amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance'
            ], 422);
        }

        //clear user from otp table
        Otp::where('user_id', $user_id)->delete();

        //generate otp
        $otp = rand(1000, 9999);
        $recipient = $user->email;
        mail::to($recipient)->send(new WithdrawalPinMail($otp));

        //store otp in database
        $pin = new Otp();
        $pin->user_id = $request->user_id;
        $pin->pin = $otp;
        $pin->save();

        return response()->json([
            'status' => true,
            'otp' => $otp,
            'message' => 'Please check your email for OTP.'
        ], 200);


    }

    public function verifyWithdrawal($user_id, Request $request)
    {
        //get user
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        //Find Bank Account else ask user to update bank account
        $userbank = Bank::where('user_id', $user_id)->first();
        if (!$userbank) {
            return response()->json([
                'status' => false,
                'message' => 'Update your bank details'
            ], 404);
        }

        //validate request
        $request->validate([
            'otp' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        //get user stash
        $stash = $user->stash;
        if (!$stash) {
            return response()->json([
                'status' => false,
                'message' => 'Stash not found'
            ], 404);
        }

        //check if user has enough balance
        if ($stash->amount < $request->amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance'
            ], 422);
        }

        //find otp from otp table
        //check if otp exists and was created within 20 minutes
        $otp = Otp::where('user_id', $user_id)->where('pin', $request->otp)->first();
        if (!$otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        //check if otp is expired
        if (now()->diffInMinutes($otp->created_at) > 20) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        //delete otp
        $otp->delete();

        //create stash history
        $stashHistory = new Stashhistory();
        $stashHistory->user_id = $user->id;
        $stashHistory->amount = $request->amount;
        $stashHistory->title = 'Withdrawal of ' . $request->amount . ' is processing.';
        $stashHistory->status = 'completed';
        $stashHistory->type = 'withdrawal';
        $stashHistory->save();

        //Store to Withdrawal table
        $withdrawal = new Withdrawal();
        $withdrawal->user_id = $user_id;
        $withdrawal->amount = $request->amount;
        $withdrawal->save();

        $this->transferToLocalBank->makeTransfer($withdrawal->id, $user_id, $userbank);

    }
}
