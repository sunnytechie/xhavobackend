<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";
    use HasFactory;

    //belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //belongs to merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    //belongs to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
