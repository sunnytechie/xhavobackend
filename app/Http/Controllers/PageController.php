<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //terms and condition
    public function terms() {
        return view('pages.terms');
    }
    //privacy policy
    public function policy() {
        return view('pages.policy');
    }
}
