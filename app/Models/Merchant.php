<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchants';
    use HasFactory;

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
}
