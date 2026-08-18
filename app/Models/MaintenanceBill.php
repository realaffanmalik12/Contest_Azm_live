<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'bill_number',
        'billing_month',
        'amount_due',
        'water_charges',
        'security_charges',
        'repair_charges',
        'other_charges',
        'penalty',
        'due_date',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'billing_month' => 'date',
            'due_date' => 'date',
        ];
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bill_id');
    }
}