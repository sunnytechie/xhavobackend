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
            'status' => 'success',
            'categories' => $categories,
        ]);
    }

    //merchant by category
    public function merchantByCategory($category_id) {
        //$merchants = Category::find($category_id)->merchants;
        $merchants = Category::with('merchants.reviews', 'merchants.user.thumbnails', 'merchants.user.workschedules')->find($category_id);

        return response()->json([
            'status' => 'success',
            'merchants' => $merchants,
        ]);
    }
}
