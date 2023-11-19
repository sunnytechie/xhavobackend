<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    //send otp
    public function sendOtp(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $otp = rand(1000, 9999);
        //store otp in database
        $user = User::where('email', $request->email)->first();
        $user->otp = $otp;
        $user->save();

        //send otp via email
        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => true,
            'message' => 'Please check your email.',
        ]);
    }

    //verify otp
    public function verifyOtp(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        //make sure user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 422);
        }

        //make sure otp is not expired
        if (now()->diffInMinutes($user->updated_at) > 20) {
            return response()->json([
                'status' => false,
                'message' => 'Pin expired',
            ], 422);
        }

        if ($user->otp == $request->otp) {
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'status' => true,
                'user_id' => $user->id,
                'message' => 'Email verified successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Pin',
            ], 422);
        }
    }
}
