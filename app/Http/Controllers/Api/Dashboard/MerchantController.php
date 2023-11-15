<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use App\Models\Booking;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
{
    public function index($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ]);
        }

        $merchant = Merchant::where('user_id', $user_id)->first();
        if (!$merchant) {
            return response()->json([
                'status' => false,
                'message' => 'User is not a merchant.',
            ]);
        }

        $thumbnails = User::find($user_id)->with('thumbnails');
        $reviews = User::find($user_id)->with('reviews');

        $currentYear = date('Y');
        $monthlyBookings = Booking::where('user_id', $user_id)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as booking_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'status' => true,
            'thumbnails' => $thumbnails,
            'chart' => $monthlyBookings,
            'reviews' => $reviews,
        ]);
    }
}
