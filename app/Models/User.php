<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function residentProfile()
    {
        return $this->hasOne(ResidentProfile::class);
    }

    public function assignedComplaints()
    {
        return $this->hasMany(Complaint::class, 'assigned_staff_id');
    }

    public function gateLogs()
    {
        return $this->hasMany(GateLog::class, 'guard_id');
    }

    public function notices()
    {
        return $this->hasMany(Notice::class, 'published_by');
    }

    public function polls()
    {
        return $this->hasMany(Poll::class, 'created_by');
    }

    public function pollVotes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function emergencyAlerts()
    {
        return $this->hasMany(EmergencyAlert::class, 'triggered_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}