<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    use HasFactory;

    //user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
