<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Merchant;

class UserController extends Controller
{
    public function merchant()
    {
        $merchants = Merchant::all();
        return view('dashboard.users.merchants', compact('merchants'));
    }

    public function customer()
    {
        $customers = Customer::all();
        return view('dashboard.users.customers', compact('customers'));
    }
}
