<?php

namespace App\Http\Controllers\Api\Booking;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Merchant;

class BookingController extends Controller
{
    //get all bookings with user id
    public function customer($user_id)
    {
        $bookings = Booking::where('user_id', $user_id)->with('merchant.user')->get();

        return response()->json([
            'status' => true,
            'message' => 'Customer bookings',
            'data' => $bookings,
        ]);
    }

    //get customer bookings
    public function merchant($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User is not found',
            ]);
        }

        $merchant = Merchant::where('user_id', $user_id)->first();
        if (!$merchant) {
            return response()->json([
                'status' => false,
                'message' => 'Merchant is not found',
            ]);
        }

        $bookings = Booking::where('merchant_id', $merchant->id)->with('user')->get();

        return response()->json([
            'status' => true,
            'message' => 'Merchant bookings',
            'data' => $bookings,
        ]);
    }

    //Chart
    public function chart($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ]);
        }

        //$currentYear = date('Y');
        //$monthlyBookings = Booking::where('user_id', $user_id)
        //    ->whereYear('created_at', $currentYear)
        //    ->selectRaw('MONTH(created_at) as month, COUNT(*) as booking_count')
        //    ->groupBy('month')
        //    ->orderBy('month')
        //    ->get();

        $currentYear = date('Y');

        // Fetch monthly bookings
        $monthlyBookings = Booking::where('user_id', $user_id)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as period, COUNT(*) as booking_count')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Fetch daily bookings for the current month
        $dailyBookings = Booking::where('user_id', $user_id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', date('m'))
            ->selectRaw('DAY(created_at) as period, COUNT(*) as booking_count')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

            // Create an array with all days of the current month
            $daysInMonth = range(1, date('t', strtotime(date('Y-m'))));

            // Fill in missing days with zero bookings
            $dailyBookings = collect($dailyBookings)->mapWithKeys(function ($item) {
                return [$item->period => $item->booking_count];
            })->toArray();

            $dailyBookings = array_replace(array_fill_keys($daysInMonth, 0), $dailyBookings);

            return response()->json([
                'status' => true,
                'message' => 'January to December booking Chart',
                'data' => [
                    'monthly' => $monthlyBookings,
                    'daily' => $dailyBookings,
                ],
            ]);


        // Fetch monthly bookings
        //$monthlyBookings = Booking::where('user_id', $user_id)
        //    ->whereYear('created_at', $currentYear)
        //    ->selectRaw('MONTH(created_at) as period, COUNT(*) as booking_count')
        //    ->groupBy('period')
        //    ->orderBy('period')
        //    ->get();

        // Fetch daily bookings
        //$dailyBookings = Booking::where('user_id', $user_id)
        //    ->whereYear('created_at', $currentYear)
        //    ->selectRaw('DATE(created_at) as period, COUNT(*) as booking_count')
        //    ->groupBy('period')
        //    ->orderBy('period')
        //    ->get();

        //return response()->json([
        //    'status' => true,
        //    'message' => 'January to December booking Chart',
        //    'data' => [
        //        'monthly' => $monthlyBookings,
        //        'daily' => $dailyBookings,
        //    ],
        //]);

    }

    //get all accepted bookings with user id
    public function accepted($user_id)
    {
        $bookings = Booking::with('user', 'merchant.user')->where('user_id', $user_id)->where('booking_status', 'accepted')->get();

        return response()->json([
            'status' => true,
            'message' => 'User accepted bookings',
            'data' => $bookings,
        ]);
    }

    //get all rejected bookings with user id
    public function rejected($user_id)
    {

        $bookings = Booking::with('user', 'merchant.user')->where('user_id', $user_id)->where('booking_status', 'rejected')->get();

        return response()->json([
            'status' => true,
            'message' => 'User rejected bookings',
            'data' => $bookings,
        ]);
    }

    //get all completed bookings with user id
    public function completed($user_id)
    {

        $bookings = Booking::with('user', 'merchant.user')->where('user_id', $user_id)->where('booking_status', 'completed')->get();

        return response()->json([
            'status' => true,
            'message' => 'User completed bookings',
            'data' => $bookings,
        ]);
    }



    public function acceptedMerchantBookings($user_id)
    {
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
                'message' => 'Not a merchant.',
            ]);
        }

        $bookings = Booking::with('user')->where('merchant_id', $merchant->id)->where('booking_status', 'accepted')->get();

        return response()->json([
            'status' => true,
            'message' => 'Your accepted bookings',
            'data' => $bookings,
        ]);
    }


    public function rejectedMerchantBookings($user_id)
    {
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
                'message' => 'Not a merchant.',
            ]);
        }

        $bookings = Booking::with('user')->where('merchant_id', $merchant->id)->where('user_id', $user_id)->where('booking_status', 'rejected')->get();

        return response()->json([
            'status' => true,
            'message' => 'User rejected bookings',
            'data' => $bookings,
        ]);
    }


    public function completedMerchantBookings($user_id)
    {
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
                'message' => 'Not a merchant.',
            ]);
        }

        $bookings = Booking::with('user')->where('merchant_id', $merchant->id)->where('user_id', $user_id)->where('booking_status', 'completed')->get();

        return response()->json([
            'status' => true,
            'message' => 'User completed bookings',
            'data' => $bookings,
        ]);
    }





    //get all current user accepted bookings
    public function acceptedBookings(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $bookings = Booking::with('user', 'merchant.user')->where('user_id', $request->user_id)->where('booking_status', 'accepted')->get();

        return response()->json([
            'status' => true,
            'message' => 'Accepted bookings',
            'data' => $bookings,
        ]);
    }

    //new booking
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'user_id' => 'required',
            'merchant_id' => 'required',
            'method_of_identity' => 'nullable',
            'identity_image' => 'nullable',
            'identity_number' => 'nullable',
        ]);

        //find user by $request->user_id
        $user = User::find($request->user_id);
        //check if user exist
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }

        //check if request does not have identity image
        if (!$request->hasFile('identity_image')) {
            //check if user->identity is not null
            if ($user->identity == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please update your KYC details.',
                ]);
            }
        } else {
            //check if user->identity is not null
            if ($user->identity == null) {
                //store identity image
                if ($request->hasFile('identity_image')) {
                    $image = $request->file('identity_image');
                    $image_name = time() . '.' . $image->extension();
                    $image->move(public_path('uploads/identity'), $image_name);
                }
                $user->identity_image = $image_name;
                $user->identity_number = $request->identity_number;
                $user->identity = $request->method_of_identity;
                $user->save();
            }
        }

        //check if user->identity is not null
        if ($user->identity == null) {
            return response()->json([
                'status' => false,
                'message' => 'Please update your KYC details.',
            ]);
        }


        $booking = new Booking();
        $booking->user_id = $request->user_id;
        $booking->merchant_id = $request->merchant_id;
        $booking->booking_date = now()->format('Y-m-d');
        $booking->booking_time = time();
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ]);
    }

    //accept booking
    public function accept(Request $request, $user_id)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->booking_status = 'accepted';
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking accepted successfully',
            'data' => $booking,
        ]);
    }

    //reject booking
    public function reject(Request $request, $user_id)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->booking_status = 'rejected';
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking rejected successfully',
            'data' => $booking,
        ]);
    }

    //complete booking
    public function complete(Request $request, $user_id)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->booking_status = 'completed';
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking completed successfully',
            'data' => $booking,
        ]);
    }

}
