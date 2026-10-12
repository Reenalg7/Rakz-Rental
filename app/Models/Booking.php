<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Booking model
 * ---------------------------------------------------------------------------
 * - References vehicle via `vehicle_registration_no` (NOT a numeric vehicle id).
 * - `status`: pending | confirmed | cancelled | completed
 * - Keeps original_vehicle_registration_no so we never lose history when
 *   admins swap out the vehicle via a VehicleReplacement.
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_registration_no',
        'original_vehicle_registration_no',
        'pickup_date',
        'return_date',
        'total_days',
        'daily_rate_snapshot',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'daily_rate_snapshot' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_registration_no', 'registration_no');
    }

    public function originalVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'original_vehicle_registration_no', 'registration_no');
    }

    public function replacements(): HasMany
    {
        return $this->hasMany(VehicleReplacement::class, 'booking_id');
    }

    public function billing(): HasOne
    {
        return $this->hasOne(Billing::class, 'booking_id');
    }

    /** Convenience: has this booking been replaced? */
    public function wasReplaced(): bool
    {
        return !is_null($this->original_vehicle_registration_no)
            && $this->original_vehicle_registration_no !== $this->vehicle_registration_no;
    }
}
