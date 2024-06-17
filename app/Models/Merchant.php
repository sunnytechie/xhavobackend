<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Merchant extends Model
{
    use Searchable, HasFactory;

    protected $table = 'merchants';

    //one to one relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //has many relationship with review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //has many relationship with thumbnail
    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class);
    }

    //has many relationship with booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    //has many relationship with notification
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    //has many relationship with report
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    //relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //relationship with category
    public function savedmerchant()
    {
        return $this->belongsTo(Savedmerchant::class);
    }

    //relationship with payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        unset($array['created_at, updated_at']);

        return  [
            'brand_name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            //'category_title' => $this->category_title,
            //'subcategory_title' => $this->subcategory_title,
        ];
    }
}
