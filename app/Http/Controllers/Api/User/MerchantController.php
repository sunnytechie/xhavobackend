<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
{
    //list of users with user_type = merchants
    public function index()
    {
        //

        $merchants = User::where('user_type', 'merchant')
                        ->with('merchant')
                        ->with('reviews')
                        ->get();

        return response()->json([
            'success' => true,
            'message' => 'List of all merchants',
            'data' => $merchants
        ]);
    }
}
