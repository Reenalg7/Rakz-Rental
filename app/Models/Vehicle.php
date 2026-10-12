<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Vehicle model
 * ---------------------------------------------------------------------------
 * LECTURER REQUIREMENT: The vehicle's registration number (e.g. "LR3180")
 * is the primary key. All related tables (bookings, replacements) reference
 * `registration_no` directly instead of a numeric vehicle id.
 *
 * Status values:
 *   available   - open for new bookings
 *   maintenance - temporarily out of service
 *   retired     - permanently removed (soft retire; history preserved)
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    /** The primary key is the registration number string. */
    protected $primaryKey = 'registration_no';

    /** Primary key is a string, not an auto-incrementing integer. */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'registration_no',
        'name',
        'make',
        'model',
        'year',
        'category_id',
        'daily_rate',
        'seats',
        'transmission',
        'fuel_type',
        'description',
        'image_url',
        'status',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'year' => 'integer',
        'seats' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /** Bookings reference the vehicle's registration_no as foreign key. */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'vehicle_registration_no', 'registration_no');
    }

    /**
     * Check if THIS vehicle is available between the two given dates.
     * Rejects overlap with any confirmed/pending booking.
     *
     * Overlap rule:
     *   existing.pickup_date  <= requested.return_date
     *   AND existing.return_date >= requested.pickup_date
     */
    public function isAvailableBetween(string $pickup, string $return, ?int $ignoreBookingId = null): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        $query = $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('pickup_date', '<=', $return)
            ->where('return_date', '>=', $pickup);

        if ($ignoreBookingId !== null) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return $query->doesntExist();
    }
}
