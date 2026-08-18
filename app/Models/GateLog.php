<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GateLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'guard_id',
        'gate_name',
        'entry_time',
        'exit_time',
        'verification_method',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'entry_time' => 'datetime',
            'exit_time' => 'datetime',
        ];
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function guards()
    {
        return $this->belongsTo(User::class, 'guard_id');
    }
}