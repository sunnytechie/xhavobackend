<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //list of categories
    public function index() {
        $categories = Category::orderBy('created_at', 'desc')
        ->where('deleted_at', null)
        ->get();

        return response()->json([
            'status' => true,
            'categories' => $categories,
        ]);
    }

    //merchant by category
    public function merchantByCategory($category_id) {
        //$merchants = Category::find($category_id)->merchants;
        $merchants = Category::find($category_id)
        ->load(['merchants.user.thumbnails', 'merchants.reviews.user.customer', 'merchants.user.workschedules']);

        return response()->json([
            'status' => true,
            'merchants' => $merchants,
        ]);
    }
}
