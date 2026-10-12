<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * User model
 * ---------------------------------------------------------------------------
 * Represents both customers and admins. The `role` column distinguishes
 * between them ('admin' | 'customer'). Role-based middleware uses this.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'nic',
        'driver_license',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /** All bookings belonging to this user (as customer). */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /** Vehicles this user has saved as favorites. */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    /**
     * Convenience: has this user favorited the given vehicle registration?
     * Called from Blade views via `auth()->user()->hasFavorited($v)`.
     */
    public function hasFavorited(string $registrationNo): bool
    {
        return $this->favorites()
            ->where('vehicle_registration_no', $registrationNo)
            ->exists();
    }

    /** Notifications sent to this user. */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
