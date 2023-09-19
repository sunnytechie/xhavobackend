<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    protected $table = 'thumbnails';
    use HasFactory;

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
