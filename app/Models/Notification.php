<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'title',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Notification belongs to a customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Notification belongs to a booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}