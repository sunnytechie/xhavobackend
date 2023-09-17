<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    use HasFactory;

    //has many relationship with merchant and users
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //has many relationship with notification
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
