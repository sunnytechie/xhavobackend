<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    public function Logout($user_id) {
        $user = User::find($user_id);

        if (!$user) {
            return response([
                'status' => false,
                'message' => 'User not found',
            ]);
        }

        $user->update(['remember_token' => null]);

        return response([
            'status' => true,
            'message' => 'Token cleared',
        ]);
    }
}
