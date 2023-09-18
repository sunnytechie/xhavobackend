<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    use HasFactory;

    //has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    //belongs to interest
    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }
}
