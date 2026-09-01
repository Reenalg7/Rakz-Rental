<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
     protected $fillable = [
        'vehicle_id',
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

}
