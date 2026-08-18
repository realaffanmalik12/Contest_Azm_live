<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flat_id',
        'emergency_contact',
        'emergency_phone',
        'cnic',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'resident_id');
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'resident_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'resident_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'resident_id');
    }

    public function amenityBookings()
    {
        return $this->hasMany(AmenityBooking::class, 'resident_id');
    }
}