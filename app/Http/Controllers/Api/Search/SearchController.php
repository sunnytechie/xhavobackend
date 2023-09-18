<?php

namespace App\Http\Controllers\Api\Search;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    //search
    public function search(Request $request)
    {
        $query = $request->input('query');
        $merchants = Merchant::search($query)
                    ->with('user') //May need to remove this
                    ->get();
        return response()->json([
            'merchants' => $merchants,
        ], 200);
    }

    //filter by category and location
    public function filter(Request $request) {
        $category = $request->input('category');
        $location = $request->input('location');
        $merchants = Merchant::where('category_id', $category)
                    ->with('user') //May need to remove this
                    ->where('location', $location)->get();
        return response()->json([
            'merchants' => $merchants,
        ], 200);
    }
}
