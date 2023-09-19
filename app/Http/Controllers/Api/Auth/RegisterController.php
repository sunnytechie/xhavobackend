<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Mail\OtpMail;
use App\Models\Customer;
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

        $user = new User();
        ////$user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $otp = rand(1000, 9999);
        //store otp in database
        $user->otp = $otp;
        $user->save();

        //send otp via email
        Mail::to($user->email)->send(new OtpMail($otp));

        //new customer
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->save();

        //get user data with customer data
        $user = User::with('customer')->find($user->id);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Please verify your email.',
        ]);
    }

    //merchant register api
    public function merchantRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->user_type = 'merchant';
        $user->save();

        $otp = rand(1000, 9999);
        //store otp in database
        $user->otp = $otp;
        $user->save();

        //send otp via email
        Mail::to($user->email)->send(new OtpMail($otp));

        //return user data
        return response()->json([
            'status' => 'success',
            'user' => $user,
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

        $user = User::find($user_id);
        $user->state = $request->state;
        $user->city = $request->city;
        $user->category_id = $request->category;
        $user->save();

        $user = User::with('customer')->find($user->id);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'User info updated successfully',
        ]);
    }


}
