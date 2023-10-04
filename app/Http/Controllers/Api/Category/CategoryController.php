<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //list of categories
    public function index() {
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ]);
    }

    //merchant by category
    public function merchantByCategory($category_id) {
        $merchants = Category::find($category_id)->merchants;

        return response()->json([
            'status' => 'success',
            'merchants' => $merchants,
        ]);
    }
}
