<?php

namespace App\Http\Controllers\Api\Payment;

use App\Models\Otp;
use App\Models\User;
use App\Models\Stash;
use App\Models\Booking;
use App\Models\Payment;
use App\Mail\PaymentMail;
use App\Models\Stashhistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    //make payment for booking
    public function createPayment(Request $request)
    {
        //validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'merchant_id' => 'required|exists:merchants,id',
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric',
        ]);

        //get user
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        //get booking
        $booking = Booking::find($request->booking_id);

        //check if booking exists
        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        //check if booking is paid
        if ($booking->payment_status == 'paid') {
            return response()->json([
                'status' => false,
                'message' => 'Booking already paid'
            ], 400);
        }

        //check stash balance if enough
        $stash = Stash::where('user_id', $request->user_id)->first();

        //check if stash exists else create
        if (!$stash) {
            $stash = new Stash();
            $stash->user_id = $request->user_id;
            $stash->amount = 0;
            $stash->save();
        }

        //can not pay less than 100
        if ($request->amount < 50) {
            return response()->json([
                'status' => false,
                'message' => 'Minimum amount to pay is 50'
            ], 400);
        }

        if ($stash->amount < $request->amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        //generate otp
        $otp = rand(1000, 9999);
        $recipient = $user->email;
        mail::to($recipient)->send(new PaymentMail($otp));

        //store otp in database
        $pin = new Otp();
        $pin->user_id = $request->user_id;
        $pin->pin = $otp;
        $pin->save();

        //return response
        return response()->json([
            'status' => true,
            'otp' => $otp,
            'message' => 'OTP sent to email',
        ], 201);
    }

    //verify otp and then update booking to paid
    public function verifyPayment(Request $request)
    {
        //validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'merchant_id' => 'required|exists:merchants,id',
            'booking_id' => 'required|exists:bookings,id',
            'otp' => 'required|numeric',
        ]);

        //check if otp exists and was created within 20 minutes
        $otp = Otp::where('user_id', $request->user_id)->where('pin', $request->otp)->first();
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


        //get booking
        $booking = Booking::find($request->booking_id);

        //check if booking exists
        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        //check if booking is paid
        if ($booking->payment_status == 'paid') {
            return response()->json([
                'status' => false,
                'message' => 'Booking already paid'
            ], 400);
        }

        $stash = Stash::where('user_id', $request->user_id)->first();
        //check if stash exists else create
        if (!$stash) {
            $stash = new Stash();
            $stash->user_id = $request->user_id;
            $stash->amount = 0;
            $stash->save();
        }

        //can not pay less than 100
        if ($request->amount < 50) {
            return response()->json([
                'status' => false,
                'message' => 'Minimum amount to pay is 50'
            ], 400);
        }

        if ($stash->amount < $request->amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        //update stash
        $stash->update([
            'amount' => $stash->amount - $request->amount
        ]);

        //find merchant
        $merchant = Merchant::find($request->merchant_id);

        //check if merchant exists
        if (!$merchant) {
            return response()->json([
                'status' => false,
                'message' => 'Merchant not found'
            ], 404);
        }

        //find merchant user
        $merchantUser = $merchant->user;

        //find user stash
        $merchantStash = $merchantUser->stash;
        if (!$merchantStash) {
            //create stash
            $merchantStash = new Stash();
            $merchantStash->user_id = $merchantUser->id;
            $merchantStash->amount = 0;
            $merchantStash->save();
        }

        //update merchant stash
        $merchantStash->update([
            'amount' => $merchantStash->amount + $request->amount
        ]);

        //create payment
        $payment = new Payment();
        $payment->user_id = $request->user_id;
        $payment->merchant_id = $request->merchant_id;
        $payment->booking_id = $request->booking_id;
        $payment->amount = $request->amount;
        $payment->save();

        //Check if user has a referral

        //update booking status to paid
        $booking->update([
            'payment_status' => 'paid'
        ]);

        //delete otp
        $otp->delete();

        //stash history
        $stashhistory = new Stashhistory();
        $stashhistory->user_id = $request->user_id;
        $stashhistory->amount = $request->amount;
        $stashhistory->title = 'Paid for booking';
        $stashhistory->type = 'payment';
        $stashhistory->status = 'completed';
        $stashhistory->save();

        //merchant stash history
        $merchantStashhistory = new Stashhistory();
        $merchantStashhistory->user_id = $merchantUser->id;
        $merchantStashhistory->amount = $request->amount;
        $merchantStashhistory->title = 'Received payment for booking';
        $merchantStashhistory->type = 'payment';
        $merchantStashhistory->status = 'completed';
        $merchantStashhistory->save();

        //return response
        return response()->json([
            'status' => true,
            'message' => 'Payment made successfully',
            'data' => $payment
        ], 201);
    }
}
