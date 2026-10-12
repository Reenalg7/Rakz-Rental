<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * BookingConfirmed email
 * ---------------------------------------------------------------------------
 * Sent to the customer when:
 *   - a new booking is created (status = pending), OR
 *   - an admin confirms the booking (status becomes confirmed).
 *
 * The $stage flag ("created" or "confirmed") lets the Blade view adapt the
 * subject line / opening message without needing two separate mailables.
 */
class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking, public string $stage = 'created')
    {
        $booking->loadMissing(['user', 'vehicle.category', 'billing']);
    }

    public function envelope(): Envelope
    {
        $subject = $this->stage === 'confirmed'
            ? "Booking Confirmed · Rakz Rental #{$this->booking->id}"
            : "Booking Received · Rakz Rental #{$this->booking->id}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-confirmed');
    }
}
