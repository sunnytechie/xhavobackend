<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'state',
        'city',
        'category_id',
        'user_type',
        'identity',
        'identity_image',
        'identity_number',
    ];

    //has many relationship with interest
    public function interests()
    {
        return $this->hasMany(Interest::class);
    }

    //has many relationship with workschedule
    public function workschedules()
    {
        return $this->hasMany(Workschedule::class)->orderBy('sortDay', 'asc');
    }

    //has many relationship with thumbnail
    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class);
    }

    //belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //one to one relationship with merchant
    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

    //has many relationship with review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //has many Savedmechant
    public function savedmerchants()
    {
        return $this->hasMany(Savedmerchant::class);
    }

    //has many relationship with booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    //has many relationship with notification
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    //has many relationship with report
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    //has one relationship with customer
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
