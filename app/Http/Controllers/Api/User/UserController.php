<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userInfo($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }

        $data = User::find($user_id)
        ->load(['merchant.user.thumbnails', 'merchant.reviews.user', 'merchant.user.workschedules', 'customer', 'interests']);
        $user_type = $user->user_type;

        return response()->json([
            'status' => true,
            'user_type' => $user_type,
            'user' => $user,
            'data' => $data
        ]);
    }
}
