<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleReplacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_vehicle_id',
        'new_vehicle_id',
        'admin_id',
    ];

    /**
     * The vehicle that was replaced.
     */
    public function oldVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'old_vehicle_id');
    }

    /**
     * The replacement vehicle.
     */
    public function newVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'new_vehicle_id');
    }

    /**
     * The admin who performed the replacement.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}