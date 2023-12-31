<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savedmerchant extends Model
{
    protected $table = "savedmerchants";

    use HasFactory;

    //relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }

}
