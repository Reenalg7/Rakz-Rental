<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * VehicleReplaced email
 * ---------------------------------------------------------------------------
 * Sent to the customer when an admin swaps the vehicle on their booking.
 * Contains the original + new registration number plus the reason.
 */
class VehicleReplaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public Vehicle $originalVehicle,
        public Vehicle $replacementVehicle,
        public ?string $reason = null,
    ) {
        $booking->loadMissing(['user']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Vehicle Replaced on Booking #{$this->booking->id} · Rakz Rental"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.vehicle-replaced');
    }
}
