<?php

namespace App\Http\Controllers\Api\Interest;

use App\Models\User;
use App\Models\Interest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InterestController extends Controller
{
    //index
    public function index($user_id) {
        $interests = Interest::with('category')->where('user_id', $user_id)->get();

        return response()->json([
            'status' => true,
            'interests' => $interests,
        ]);
    }

    //store interests
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'categories' => 'required',
        ]);

        $user = User::find($user_id);

        $categories = json_decode($request->categories);

        foreach ($categories as $category) {
            $interest = new Interest();
            $interest->category_id = $category->id;
            $interest->user_id = $user->id;
            $interest->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Interest added successfully',
        ]);
    }
}
