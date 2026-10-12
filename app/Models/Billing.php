<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Billing model
 * ---------------------------------------------------------------------------
 * Internal billing record for a booking. We do NOT integrate a real payment
 * gateway in this project. Admins can mark a bill as paid manually.
 *
 * status: unpaid | paid | refunded
 */
class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'status',
        'payment_method',
        'paid_at',
        'reference_no',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
