<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

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
}
