<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use App\Models\Booking;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Thumbnail;

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

        $thumbnails = Thumbnail::where('user_id', $user_id)->get();
        $reviews = Review::with('user')->where('merchant_id', $merchant->id)->get();

        $currentYear = date('Y');
        $monthlyBookings = Booking::where('user_id', $user_id)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as booking_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'status' => true,
            'brandName' => $merchant->brand_name,
            'thumbnails' => $thumbnails,
            'chart' => $monthlyBookings,
            'reviews' => $reviews,
        ]);
    }
}
