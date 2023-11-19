<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //login api
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Wrong Password or Email.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        //if user email is not verified
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => false,
                'message' => 'Please verify your email.',
            ], 401);
        }

        //if user user_type is customer get user with customer
        if ($user->user_type == 'customer') {
            $user = User::with('customer')->find($user->id);
        }
        //if user_type is merchant get user with merchant
        if ($user->user_type == 'merchant') {
            $user = User::with('merchant')->find($user->id);
        }

        switch ($user->user_type) {
            case 'customer':
                $user_type = "customer";
                break;

            case 'merchant':
                $user_type = "merchant";
                break;

            case 'admin':
                $user_type = "admin";
                break;

            default:
                $user_type = "None";
                break;
        }

        return response()->json([
            'status' => true,
            'user_type' => $user_type,
            'user' => $user,
            'token' => $token,
        ]);
    }
}
