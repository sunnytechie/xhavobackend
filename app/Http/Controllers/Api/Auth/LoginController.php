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
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        //if user email is not verified
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => 'error',
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

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
