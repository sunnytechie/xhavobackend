<?php

namespace App\Http\Controllers\Api\ScoutSearch;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    //search
    public function searchText(Request $request)
    {
        $query = $request->input('query');
        $merchants = Merchant::search($query)
                    //->with('user') //May need to remove this
                    ->get();

        // Eager load the 'user' relationship
        $merchants->load(['user', 'reviews.user.customer', 'user.thumbnails', 'user.workschedules']);

        return response()->json([
            'status' => true,
            'merchants' => $merchants,
        ], 200);
    }

    //filter by category and location
    public function filter(Request $request) {
        try {
            $location = $request->input('location');

            if ($request->has('categories')) {
                $categories = json_decode($request->categories);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Handle JSON decoding error
                    return response()->json([
                        'status' => false,
                        'merchants' => null,
                    ], 400);
                }

                if (!empty($categories)) {
                    $query = Merchant::with(['user', 'reviews.user.customer', 'user.thumbnails', 'user.workschedules'])
                        ->whereIn('category_id', $categories);

                    //if ($location) {
                    //    $query->where('location', $location);
                    //}

                    if ($location) {
                        $query->whereIn('id', Merchant::search($location)->get()->pluck('id'));
                    }

                    $merchants = $query->get();
                } else {
                    // Handle the case where categories is an empty array
                    $merchants = Merchant::with(['user', 'reviews.user.customer', 'user.thumbnails', 'user.workschedules'])->get();
                }
            } else {
                // Handle the case where categories is not provided
                $merchants = Merchant::with(['user', 'reviews.user.customer', 'user.thumbnails', 'user.workschedules'])->get();
            }

            return response()->json([
                'status' => true,
                'merchants' => $merchants,
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'merchants' => null,
            ], 400);

        }
    }
}
