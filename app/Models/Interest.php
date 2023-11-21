<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $table = 'interests';
    use HasFactory;

    //belongs to many relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //has one relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
