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

        //$data = User::find($user_id)
        //->load(['thumbnails', 'merchant.reviews.user.customer', 'workschedules', 'customer', 'interests']);

        if ($user->user_type == 'customer') {
            $data = User::find($user->id)
            ->load(['customer.user.interests'])->find($user->id);
        }
        //if user_type is merchant get user with merchant
        if ($user->user_type == 'merchant') {
            $data = User::find($user->id)
            ->load(['thumbnails', 'merchant.reviews.user.customer', 'merchant.user.workschedules'])->find($user->id);
        }

        $user_type = $user->user_type;

        return response()->json([
            'status' => true,
            'user_type' => $user_type,
            'user' => $data,
            //'data' => $data
        ]);
    }
}
