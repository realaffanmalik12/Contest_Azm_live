<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_name',
        'flat_number',
        'floor',
        'occupancy_type',
        'status',
    ];

    public function residentProfiles()
    {
        return $this->hasMany(ResidentProfile::class);
    }

    public function maintenanceBills()
    {
        return $this->hasMany(MaintenanceBill::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
}