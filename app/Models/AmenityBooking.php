<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity_id',
        'resident_id',
        'booking_date',
        'start_time',
        'end_time',
        'purpose',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function resident()
    {
        return $this->belongsTo(ResidentProfile::class, 'resident_id');
    }
}