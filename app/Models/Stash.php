<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stash extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    use HasFactory;

    //belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
