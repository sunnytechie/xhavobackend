<?php

namespace App\Http\Controllers\Api\ScoutSearch;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    //search
    public function searchText(Request $request)
    {
        //$query = $request->input('query');
        //$merchants = Merchant::search($query)
                    //->with('user') //May need to remove this
                    //->get();

                    $query = $request->input('query');

                    // Step 1: Use Scout search to retrieve categories that match the search query
                    $categories = Category::search($query)->get()->pluck('id');

                    // Step 2: Retrieve merchants that belong to those categories
                    $merchantsByCategory = Merchant::whereIn('category_id', $categories)->get();

                    // Step 3: Use Scout to retrieve merchants that match the search query directly
                    $merchantsDirectSearch = Merchant::search($query)->get();

                    // Step 4: Combine results and remove duplicates
                    $merchants = $merchantsByCategory->merge($merchantsDirectSearch)->unique('id');

                    // Optionally, if you need to include user relation
                    //$merchants->load('user');



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
