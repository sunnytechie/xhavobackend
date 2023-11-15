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
        $merchants->load(['user', 'reviews', 'user.thumbnails', 'user.workschedules']);

        return response()->json([
            'merchants' => $merchants,
        ], 200);
    }

    //filter by category and location
    public function filter(Request $request) {
        $category = $request->input('category');
        $location = $request->input('location');

        $merchants = Merchant::with(['user', 'reviews', 'user.thumbnails', 'user.workschedules']) //May need to remove this
                    ->where('category_id', $category)
                    ->orWhere('location', $location)->get();

        return response()->json([
            'merchants' => $merchants,
        ], 200);
    }
}
