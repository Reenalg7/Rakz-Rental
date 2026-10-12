<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * VehicleReplacement model
 * ---------------------------------------------------------------------------
 * Audit record for every admin-initiated vehicle swap on a booking.
 * Links both the "from" and "to" vehicles by registration_no (not by numeric id)
 * so we match the lecturer's primary-key requirement across the whole schema.
 */
class VehicleReplacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'original_registration_no',
        'replacement_registration_no',
        'reason',
        'replaced_by_user_id',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function originalVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'original_registration_no', 'registration_no');
    }

    public function replacementVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'replacement_registration_no', 'registration_no');
    }

    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replaced_by_user_id');
    }
}
