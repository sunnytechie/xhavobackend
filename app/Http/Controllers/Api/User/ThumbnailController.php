<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Thumbnail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThumbnailController extends Controller
{
    //store thumbnail
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        //store image
        $image = $request->file('image');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('merchants/cover'), $image_name);

        $thumbnail = new Thumbnail();
        $thumbnail->user_id = $user_id;
        $thumbnail->image = $image_name;
        $thumbnail->save();

        return response()->json([
            'status' => true,
            'message' => 'Thumbnail created successfully',
            'thumbnail' => $thumbnail,
        ]);
    }

    //update thumbnail
    public function update(Request $request, $user_id, $thumbnail_id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        //store image
        $image = $request->file('image');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('merchants/cover'), $image_name);

        $thumbnail = Thumbnail::find($thumbnail_id);
        $thumbnail->user_id = $user_id;
        $thumbnail->image = $image_name;
        $thumbnail->save();

        return response()->json([
            'status' => true,
            'message' => 'Thumbnail updated successfully',
            'thumbnail' => $thumbnail,
        ]);
    }
}
