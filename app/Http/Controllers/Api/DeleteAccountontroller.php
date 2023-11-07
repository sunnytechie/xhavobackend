<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteAccountontroller extends Controller
{
    //delete
    public function deleteAccount(Request $request){
        dd($request->all());
    }
}
