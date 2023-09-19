<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workschedule extends Model
{
    protected $table = 'workschedules';
    use HasFactory;

    //relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
