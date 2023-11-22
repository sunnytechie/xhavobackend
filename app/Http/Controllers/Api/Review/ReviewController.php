<?php

namespace App\Http\Controllers\Api\Review;

use App\Models\User;
use App\Models\Review;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user_id)
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
                'message' => 'User is not a merchant.',
            ]);
        }

        $review = Review::with('user')->where('merchant_id', $merchant->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'requested user reviews',
            'data' => $review
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'user_id' => 'required',
            'merchant_id' => 'required',
            'rating' => 'required',
            'comment' => 'required'
        ]);

        dd('checking.');

        //if validation fails
        if($request->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'data' => $request->errors()
            ]);
        }

        //new review
        $review = new Review();
        $review->user_id = $request->user_id;
        $review->merchant_id = $request->merchant_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        //return response
        return response()->json([
            'status' => true,
            'message' => 'Review created successfully',
            'data' => $review
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //validate request
        $request->validate([
            'rating' => 'required',
            'comment' => 'required'
        ]);

        //if validation fails
        if($request->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'data' => $request->errors()
            ]);
        }

        //update review
        $review = Review::find($id);
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        //return response
        return response()->json([
            'status' => true,
            'message' => 'Review updated successfully',
            'data' => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //delete review
        $review = Review::find($id);
        $review->delete();

        //return response
        return response()->json([
            'status' => true,
            'message' => 'Review deleted successfully',
            'data' => $review
        ]);
    }
}
