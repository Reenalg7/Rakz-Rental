<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'replacement_vehicle_id',
        'customer_name',
        'pickup_date',
        'return_date',
        'total_amount',
        'status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replacementVehicle()
    {
    return $this->belongsTo(
        Vehicle::class,
        'replacement_vehicle_id');
    }
}
