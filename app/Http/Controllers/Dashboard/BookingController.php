<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();
        return view('dashboard.bookings', compact('bookings'));
    }
}
