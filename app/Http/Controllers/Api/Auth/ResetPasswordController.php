<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    //forgot password api collect email and send otp
    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email does not exist.',
            ], 401);
        }

        $otp = rand(1000, 9999);
        //store otp in database
        $user->otp = $otp;
        $user->save();

        //send otp via email
        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'message' => 'Otp sent successfully.',
        ]);
    }

    //check otp sent matched
    public function otpCheck(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required',
            //'password' => 'required|min:6',
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
                'message' => 'User does not exist.',
            ], 401);
        }

        ////make sure otp is correct
        if ($user->otp != $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid otp.',
            ], 401);
        }

        ////$user->password = bcrypt($request->password);
        ////$user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Proceed to reset password',
        ]);
    }

    //reset password api
    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required',
            'password' => 'required|min:6',
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
                'message' => 'User does not exist.',
            ], 401);
        }

        ////make sure otp is correct
        if ($user->otp != $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid otp.',
            ], 401);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        //delete otp // future update

        return response()->json([
            'status' => 'success',
            'message' => 'Proceed to reset password',
        ]);
    }

    //change password
    public function changePassword(Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::find($user_id);

        //make sure user exists
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not exist.',
            ], 401);
        }

        //make sure old password is correct
        if (!password_verify($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Old password is incorrect.',
            ], 401);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.',
        ]);
    }
}
