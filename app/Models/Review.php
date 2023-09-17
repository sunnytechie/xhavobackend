<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    use HasFactory;

    //one to one relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //one to one relationship with merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    //has many relationship with notification
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
