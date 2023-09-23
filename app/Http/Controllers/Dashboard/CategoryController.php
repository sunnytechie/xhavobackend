<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all categories
        $categories = Category::orderBy('created_at', 'desc')
        ->where('deleted_at', null)
        ->get();
        return view('dashboard.categories', compact('categories'));
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
        //validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|image|max:1024',
        ]);

        //generate unique character for image name

        //store the image in storage
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $image_name = uniqid() . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/categories'), $image_name);
        }

        //store the category in database
        $category = new Category();
        $category->title = $request->title;
        $category->thumbnail = $image_name;
        $category->save();

        //redirect to categories.index
        return redirect()->back()->with('message', 'Category created successfully');
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
        //$request->validate([
       //     'title' => 'required|string|max:255',
        //    'thumbnail' => 'nullable|image|max:1024',
       // ]);

        //dd($request->all());

        //if the request has thumbnail
        if ($request->hasFile('thumbnail')) {
            //generate unique character for image name
            //dd($request->file('thumbnail'));

            //store the image in storage
            $image = $request->file('thumbnail');
            $image_name = uniqid() . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/categories'), $image_name);

            //update the category in database
            $category = Category::find($id);
            $category->title = $request->title;
            $category->thumbnail = $image_name;
            $category->save();
        } else {
            //dd($request->all());
            //update the category in database
            $category = Category::find($id);
            $category->title = $request->title;
            $category->save();
        }

        //redirect back
        return redirect()->back()->with('message', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //delete the category from database
        $category = Category::find($id);
        $category->deleted_at = now();
        $category->save();

        //redirect back
        return redirect()->back()->with('message', 'Category deleted successfully');
    }
}
