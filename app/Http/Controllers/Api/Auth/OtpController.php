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
                'status' => 'error',
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
            'status' => 'success',
            'message' => 'Otp sent successfully',
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
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        //make sure user exists
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 422);
        }

        //make sure otp is not expired
        if (now()->diffInMinutes($user->updated_at) > 20) {
            return response()->json([
                'status' => 'error',
                'message' => 'Otp expired',
            ], 422);
        }

        if ($user->otp == $request->otp) {
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'status' => 'success',
                'user_id' => $user->id,
                'message' => 'Otp verified successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid otp',
            ], 422);
        }
    }
}
