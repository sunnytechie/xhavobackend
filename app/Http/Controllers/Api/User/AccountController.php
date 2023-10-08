<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Support;
use App\Models\Customer;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    //account update
    public function update(Request $request, $user_id)
    {
        //validate request
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string',
            //'email' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|string',
        ]);

        //save image in storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/profile'), $image_name);
        } else {
            $image_name = "https://xhavo.app/assets/images/profile/user-default.png";
        }

        //find user with user id
        $user = User::find($user_id);
        $user->name = $request->name;
        //$user->email = $request->email;
        $user->save();

        //find customer with user id
        $customer = Customer::where('user_id', $user_id)->first();
        $customer->image = $image_name;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->gender = $request->gender;
        $customer->birthday = $request->birthday;
        $customer->save();

        //find user with customer with user id
        $user = User::with('customer')->find($user_id);


        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Account updated successfully',
        ], 200);
    }

    //update merchant user account
    public function updateMerchantUser(Request $request, $user_id)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            //'email' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        //find customer with user id
        $user = User::find($user_id);
        $user->name = $request->name;
        //$user->email = $request->email;
        $user->save();

        //find merchant with user id
        $merchant = Merchant::where('user_id', $user_id)->first();
        $merchant->name = $request->name;
        $merchant->phone = $request->phone;
        $merchant->gender = $request->gender;
        $merchant->birthday = $request->birthday;
        $merchant->save();

        //return user with merchant
        $user = User::with('merchant')->find($user_id);
        //return user
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Account updated successfully',
        ], 200);

    }

    //update merchant profile
    public function updateMerchant(Request $request, $user_id) {
        //validate data
        $validator = Validator::make($request->all(), [
            'brand_name' => 'required',
            'category_id' => 'required',
            'logo' => 'required',
            'description' => 'required',
            'location' => 'required',
            'whatsapp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        //save image in storage
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/merchant'), $image_name);
        } else {
            $image_name = null;
        }

        //find merchant with user id
        $merchant = Merchant::where('user_id', $user_id)->first();
        $merchant->brand_name = $request->brand_name;
        $merchant->category_id = $request->category_id;
        $merchant->logo = $image_name;
        $merchant->description = $request->description;
        $merchant->location = $request->location;
        $merchant->whatsapp = $request->whatsapp;
        $merchant->save();

        //find user with merchant with user id
        $user = User::with('merchant')->find($user_id);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Merchant profile updated successfully',
        ], 200);
    }

    //user identity update
    public function updateIdentity(Request $request, $user_id) {
        //validate request
        $request->validate([
            'identity' => 'required|string',
            'identity_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'identity_number' => 'required|string',
        ]);

        //save image in storage
        if ($request->hasFile('identity_image')) {
            $image = $request->file('identity_image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/identity'), $image_name);
        } else {
            $image_name = null;
        }

        //find user with user id
        $user = User::find($user_id);
        $user->identity = $request->identity;
        $user->identity_image = $image_name;
        $user->identity_number = $request->identity_number;
        $user->save();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Identity updated successfully',
        ], 200);

    }

    //delete user and customer or merchant
    public function destroy($user_id) {
        //find user with user id
        $user = User::find($user_id);

        //if user is customer
        if ($user->user_type == 'customer') {
            //find customer with user id
            $customer = Customer::where('user_id', $user_id)->first();
            $customer->delete();
        }

        //if user is merchant
        if ($user->user_type == 'merchant') {
            //find merchant with user id
            $merchant = Merchant::where('user_id', $user_id)->first();
            $merchant->delete();
        }

        //delete user
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Account deleted successfully',
        ], 200);
    }

    //support message
    public function support(Request $request, $user_id) {
        //validate request
        $request->validate([
            'message' => 'required|string',
        ]);

        //find user with user id
        $user = User::find($user_id);

        //save support message
        $support = new Support();
        $support->user_id = $user_id;
        $support->message = $request->message;
        $support->save();

        //send email to admin
        //$message = $request->message;
        //$receiver = env('ADMIN_EMAIL');
        //// Code email message here

        return response()->json([
            'status' => 'success',
            'message' => 'Support message sent successfully',
        ], 200);
    }
}
