<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    protected $table = 'categories';

    use HasFactory, Searchable;

    //has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    //has many merchants
    public function merchants()
    {
        return $this->hasMany(Merchant::class);
    }

    //belongs to interest
    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        unset($array['created_at, updated_at']);

        return  [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
