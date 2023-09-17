<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    use HasFactory;

    //relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relationship with merchant
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}
