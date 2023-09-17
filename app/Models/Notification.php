<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    use HasFactory;

    //has many notifications
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //has many notifications relationship with merchant
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    //has many notifications relationship with booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    //has many notifications relationship with review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
