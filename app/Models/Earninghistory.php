<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earninghistory extends Model
{
    protected $table = 'earninghistories';
    use HasFactory;

    //belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //has many relationship with referrer
    public function referrers()
    {
        return $this->hasMany(Referrer::class);
    }
}
