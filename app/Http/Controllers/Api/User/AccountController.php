<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Support;
use App\Models\Customer;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            //'address' => 'required|string',
            //'city' => 'required|string',
            //'state' => 'required|string',
            //'country' => 'required|string',
            //'zip' => 'required|string',
        ]);

        //save image in storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/profile'), $image_name);
        } else {
            $image_name = "https://xhavo.app/assets/images/profile/user-default.png";
        }

        //find customer with user id
        $customer = Customer::where('user_id', $user_id)->first();
        $customer->image = $image_name;
        $customer->name = $request->name;
        //$customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->gender = $request->gender;
        $customer->birthday = $request->birthday;
        //$customer->address = $request->address;
        //$customer->city = $request->city;
        //$customer->state = $request->state;
        //$customer->country = $request->country;
        //$customer->zip = $request->zip;
        $customer->save();

        //find user with customer with user id
        $user = User::with('customer')->find($user_id);


        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Account updated successfully',
        ], 200);
    }

    //user identity update
    public function identity(Request $request, $user_id) {
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
