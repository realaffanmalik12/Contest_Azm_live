<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_profile_id',
        'name',
        'relationship',
        'type',
        'cnic',
        'phone',
    ];

    public function residentProfile()
    {
        return $this->belongsTo(ResidentProfile::class);
    }
}