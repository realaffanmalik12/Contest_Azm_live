<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'flat_id',
        'visitor_name',
        'phone',
        'vehicle_number',
        'visitor_type',
        'gate_pass_code',
        'qr_token',
        'valid_from',
        'valid_until',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
        ];
    }

    public function resident()
    {
        return $this->belongsTo(ResidentProfile::class, 'resident_id');
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function gateLogs()
    {
        return $this->hasMany(GateLog::class);
    }
}