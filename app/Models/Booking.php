<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'merchant_id',
        'booking_date',
        'booking_time',
        'booking_status',
        'payment_status',
        'method_of_identity',
        'identity_image',
        'identity_number',
    ];
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

    //has many relationship with payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    //has many relationship with notification
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
