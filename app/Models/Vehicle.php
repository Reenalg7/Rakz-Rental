<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\VehicleReplacement;

class Vehicle extends Model
{
    protected $fillable = [
        'registration',
        'name',
        'category',
        'daily_rate',
        'status',
    ];

    public function replacementsAsOld()
    {
    return $this->hasMany(
        VehicleReplacement::class,
        'old_vehicle_id'
    );
    }

    public function replacementsAsNew()
    {
        return $this->hasMany(
            VehicleReplacement::class,
            'new_vehicle_id'
        );
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
