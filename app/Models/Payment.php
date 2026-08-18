<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'resident_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'payment_status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function bill()
    {
        return $this->belongsTo(MaintenanceBill::class, 'bill_id');
    }

    public function resident()
    {
        return $this->belongsTo(ResidentProfile::class, 'resident_id');
    }
}