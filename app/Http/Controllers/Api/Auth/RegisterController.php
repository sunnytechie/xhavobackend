<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Mail\OtpMail;
use App\Models\Customer;
use App\Models\Interest;
use App\Models\Workschedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Referrer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    //customer register api
    public function customerRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            ////'phone' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'referrer' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        //check if referrer exists
        if ($request->has('referrer')) {
            $referrer = Referrer::where('code', $request->referrer)->first();
            if ($referrer) {
                //return response()->json([
                //    'status' => false,
                //    'message' => 'Referrer not found',
                //], 404);
            }
        }

        $user = new User();
        ////$user->phone = $request->phone;
        $user->email = $request->email;
        $user->referrer_id = $referrer->id ?? null;
        $user->password = bcrypt($request->password);
        $user->save();

        $otp = rand(1000, 9999);
        //store otp in database
        $user->otp = $otp;
        $user->save();

        //send otp via email
        Mail::to($user->email)->send(new OtpMail($otp));

        //new customer
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->save();

        //get user data with customer data
        $user = User::with('customer')->find($user->id);
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user,
            'message' => 'Please verify your email.',
        ]);
    }

    //merchant register api
    public function merchantRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'referrer' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        //check if referrer exists
        if ($request->has('referrer')) {
            $referrer = Referrer::where('code', $request->referrer)->first();
            if (!$referrer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Referrer not found',
                ], 404);
            }
        }

        $user = new User();
        $user->email = $request->email;
        $user->referrer_id = $referrer->id ?? null;
        $user->password = bcrypt($request->password);
        $user->user_type = 'merchant';
        $user->save();

        $otp = rand(1000, 9999);
        //store otp in database
        $user->otp = $otp;
        $user->save();

        //send otp via email
        Mail::to($user->email)->send(new OtpMail($otp));

        //make workschedules
        $dayNames = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        foreach ($dayNames as $day => $dayName) {
            $schedule = new Workschedule();
            $schedule->user_id = $user->id;
            $schedule->day = $dayName; // Add the day name
            $schedule->sortDay = $day;
            $schedule->start_time = '00:00 AM'; // Replace with the actual start time
            $schedule->end_time = '00:00 PM'; // Replace with the actual end time
            $schedule->save();
        }

        $user = User::with('workschedules')->find($user->id);
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        //return user data
        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user,
            'message' => 'Please verify your email.',
        ]);
    }

    //customer state, city and category selection api
    public function customerInfo(Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'state' => 'required',
            'city' => 'required',
            'categories' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        if (!$request->has('categories') || !$request->filled('categories')) {
            return response()->json([
                'status' => false,
                'message' => 'Please select at least one category',
            ], 422);
        } else {
            $categories = json_decode($request->categories);

            foreach ($categories as $category) {
                $interest = new Interest();
                $interest->category_id = $category->id;
                $interest->user_id = $user_id;
                $interest->save();
            }
        }


        $user = User::find($user_id);
        $user->state = $request->state;
        $user->city = $request->city;
        $user->save();

        $user = User::with(['customer', 'interests'])->find($user->id);

        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'User info updated successfully',
        ]);
    }


}
