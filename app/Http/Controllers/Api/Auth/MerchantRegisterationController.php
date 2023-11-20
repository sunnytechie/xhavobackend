<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MerchantRegisterationController extends Controller
{
    //register api
    public function register(Request $request, $user_id) {
        //validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'birthday' => '',
            'whatsapp' => '',
            'brand_name' => 'required',
            'category_id' => 'required',
            //'logo' => 'required',
            'description' => 'required',
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        //find category with category id from request and get the category thumbnail
        $category = Category::find($request->category_id);

        //make sure category exists
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], 404);
        }

        //category thumbnail
        $thumbnail = $category->thumbnail;

        $merchant = new Merchant();
        $merchant->user_id = $user_id;
        $merchant->name = $request->name;
        $merchant->gender = $request->gender;
        $merchant->phone = $request->phone;
        $merchant->birthday = $request->birthday;
        $merchant->whatsapp = $request->whatsapp;
        $merchant->brand_name = $request->brand_name;
        $merchant->category_id = $request->category_id;
        $merchant->logo = "/images/categories/$thumbnail";
        $merchant->description = $request->description;
        $merchant->location = $request->location;
        $merchant->save();

        //find user with merchant via user id
        $user = User::with(['merchant', 'workschedules'])->find($user_id);

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Merchant registered successfully.',
        ]);
    }
}
