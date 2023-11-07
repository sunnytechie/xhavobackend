<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Savedmerchant;
use App\Models\User;
use Illuminate\Http\Request;

class SavedmerchantController extends Controller
{

    //index
    public function index($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'user not found',
            ]);
        }

        $savedmerchants = Savedmerchant::orderBy('id', 'desc')
        //$savedmerchants = Savedmerchant::with('merchant', 'merchant.category', 'merchant.user.thumbnails', 'merchant.user.workschedules', 'merchant.user.review')
                    //->orderBy('id', 'desc')
                    ->where('user_id', $user_id)
                    ->get();

        return response()->json([
            'status' => true,
            'savedmerchants' => $savedmerchants,
        ]);
    }


    //store
    public function store($user_id, $merchant_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'user not found',
            ]);
        }

        $merchant = Merchant::find($merchant_id);
        if (!$merchant) {
            return response()->json([
                'status' => false,
                'message' => 'merchant not found',
            ]);
        }

        $checkExist = Savedmerchant::where('user_id', $user_id)
                        ->where('merchant_id', $merchant_id)
                        ->first();

        if (!$checkExist) {
            $model = new Savedmerchant();
            $model->user_id = $user_id;
            $model->merchant_id = $merchant_id;
            $model->save();

            return response()->json([
                'status' => true,
                'message' => 'saved successfully',
            ]);
        }
        else {
            $checkExist->delete();

            return response()->json([
                'status' => true,
                'message' => 'Removed successfully',
            ]);
        }


    }
}
