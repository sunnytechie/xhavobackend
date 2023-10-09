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
        $merchants = User::where('user_type', 'merchant')
                        ->with('merchant')
                        ->with('reviews')
                        ->get();

                        $merchantData = array();
                        foreach ($merchants as $merchant) {
                            //category
                            $category = Category::where('id', $merchant->merchant->category_id)->first();

                            $merchantData[] = array(
                                'category' => $category->title,
                                'merchant' => $merchant,
                            );
                        }

        return response()->json([
            'success' => true,
            'message' => 'List of all merchants',
            'data' => $merchantData
        ]);
    }
}
