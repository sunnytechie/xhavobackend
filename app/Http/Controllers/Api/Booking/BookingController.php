<?php

namespace App\Http\Controllers\Api\Booking;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    //get all bookings with user id
    public function index($user_id)
    {

        $bookings = Booking::where('user_id', $user_id)->with('user')->get();

        return response()->json([
            'status' => 200,
            'message' => 'User bookings',
            'data' => $bookings,
        ]);
    }

    //get all accepted bookings with user id
    public function accepted($user_id)
    {

        $bookings = Booking::where('user_id', $user_id)->where('booking_status', 'accepted')->get();

        return response()->json([
            'status' => 200,
            'message' => 'User accepted bookings',
            'data' => $bookings,
        ]);
    }

    //get all rejected bookings with user id
    public function rejected($user_id)
    {

        $bookings = Booking::where('user_id', $user_id)->where('booking_status', 'rejected')->get();

        return response()->json([
            'status' => 200,
            'message' => 'User rejected bookings',
            'data' => $bookings,
        ]);
    }

    //get all completed bookings with user id
    public function completed($user_id)
    {

        $bookings = Booking::where('user_id', $user_id)->where('booking_status', 'completed')->get();

        return response()->json([
            'status' => 200,
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

        $bookings = Booking::where('user_id', $request->user_id)->where('booking_status', 'accepted')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Accepted bookings',
            'data' => $bookings,
        ]);
    }

    //new booking
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'merchant_id' => 'required',
            'method_of_identity' => 'required',
            'identity_image' => 'required',
            'identity_number' => 'required',
        ]);

        //store identity image
        $image = $request->file('identity_image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('uploads/identity'), $image_name);

        $booking = Booking::create([
            'user_id' => $request->user_id,
            'merchant_id' => $request->merchant_id,
            'booking_date' => now()->format('Y-m-d'),
            'booking_time' => time(),
            'method_of_identity' => $request->method_of_identity,
            'identity_image' => $image_name,
            'identity_number' => $request->identity_number,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ]);
    }

    //accept booking
    public function accept(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->booking_status = 'accepted';
        $booking->save();

        return response()->json([
            'status' => 200,
            'message' => 'Booking accepted successfully',
            'data' => $booking,
        ]);
    }

    //reject booking
    public function reject(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->booking_status = 'rejected';
        $booking->save();

        return response()->json([
            'status' => 200,
            'message' => 'Booking rejected successfully',
            'data' => $booking,
        ]);
    }

    //complete booking
    public function complete(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->booking_status = 'completed';
        $booking->save();

        return response()->json([
            'status' => 200,
            'message' => 'Booking completed successfully',
            'data' => $booking,
        ]);
    }


}
