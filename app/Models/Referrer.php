<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referrer extends Model
{
    protected $fillable = [
        'user_id',
        'referrer_id',
        'code',
    ];

    use HasFactory;

    //belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //belongs to referrer
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    //has many relationship with earninghistory
    public function earninghistories()
    {
        return $this->hasMany(Earninghistory::class);
    }
}
