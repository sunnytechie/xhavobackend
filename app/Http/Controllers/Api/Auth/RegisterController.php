<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    //customer register api
    public function customerRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            ////'phone' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $customer = new User();
        ////$customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->password = bcrypt($request->password);
        $customer->save();

        $otp = rand(1000, 9999);
        //store otp in database
        $customer->otp = $otp;
        $customer->save();

        //send otp via email
        Mail::to($customer->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => 'success',
            'user_id' => $customer->id,
            'message' => 'Please verify your email.',
        ]);
    }

    //customer state, city and category selection api
    public function customerInfo(Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'state' => 'required',
            'city' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $customer = User::find($user_id);
        $customer->state = $request->state;
        $customer->city = $request->city;
        $customer->category_id = $request->category;
        $customer->save();

        return response()->json([
            'status' => 'success',
            'user_id' => $customer->id,
            'message' => 'Customer info updated successfully',
        ]);
    }
}
