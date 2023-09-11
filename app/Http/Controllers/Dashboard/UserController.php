<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function provider()
    {
        return view('dashboard.users.providers');
    }

    public function customer()
    {
        return view('dashboard.users.customers');
    }
}
