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
        $location = $request->input('location');

        if ($request->has('categories')) {
            $categories = json_decode($request->categories);

            if (!empty($categories)) {
                $merchants = Merchant::with(['user', 'reviews.user.customer', 'user.thumbnails', 'user.workschedules'])
                    ->whereIn('category_id', $categories)
                    ->where('location', $location)
                    ->get();
            } else {
                // Handle the case where no categories are provided
                $merchants = Merchant::get();
            }
        } else {
            // Handle the case where no categories parameter is present in the request
            $merchants = Merchant::get();
        }


        return response()->json([
            'status' => true,
            'merchants' => $merchants,
        ], 200);
    }
}
