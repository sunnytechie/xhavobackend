<?php

namespace App\Http\Controllers\Api\Pages;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Referrer;

class AffiliateController extends Controller
{
    public function index($user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        //earnings
        $earning = $user->earning;
        if (!$earning) {
            $earning = 0;
        }

        $referrer = Referrer::where('user_id', $user_id)->first();
        if (!$referrer) {
            //generate 7 letter code make sure it is unique in the referrer table else regenerate
            $code = substr(md5(uniqid(rand(), true)), 0, 7);
            $check = Referrer::where('code', $code)->first();
            while ($check) {
                $code = substr(md5(uniqid(rand(), true)), 0, 7);
                $check = Referrer::where('code', $code)->first();
            }

            //dd($code . $user->referrer_id . $user_id);

            //create referrer
            $referrer = Referrer::create([
                'user_id' => $user_id,
                'referrer_id' => $user->referrer_id,
                'code' => $code,
            ]);
        }

        $referrer = Referrer::select('code', 'id')->where('user_id', $user_id)->first();

        //dd($referrer);

        //get all users that have this user as their referrer
        $referrals = User::select('name', 'email', 'created_at')->where('referrer_id', $referrer->id)->get();

        foreach ($referrals as $referral) {
            if ($referral->name == null) {
                $referral->name = 'Customer';
            }
        }

        //return response
        return response()->json([
            'status' => true,
            'message' => 'Affiliate page',
            'data' => [
                //'user' => $user,
                'earnings' => $earning,
                'referrer' => $referrer,
                'referrals' => $referrals,
            ]
        ]);
    }
}
