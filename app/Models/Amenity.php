<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'opening_time',
        'closing_time',
        'booking_fee',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(AmenityBooking::class);
    }
}