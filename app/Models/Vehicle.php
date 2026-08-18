<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'vehicle_number',
        'vehicle_type',
        'model',
        'color',
        'status',
    ];

    public function resident()
    {
        return $this->belongsTo(ResidentProfile::class, 'resident_id');
    }
}