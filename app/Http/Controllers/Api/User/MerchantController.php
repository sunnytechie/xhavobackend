<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class MerchantController extends Controller
{
    //list of users with user_type = merchants
    public function index()
    {
        $users = User::whereHas('merchant')
        ->with(['merchant', 'thumbnails', 'reviews.merchant.user', 'workschedules'])
        ->get();

        $merchantData = array();
        foreach ($users as $user) {
            //category
            $category = Category::where('id', $user->merchant->category_id)->first();

            $merchantData[] = array(
                'category' => $category->title,
                'merchant' => $user,
            );
        }

        return response()->json([
            'success' => true,
            'data' => $merchantData
        ]);
    }
}
