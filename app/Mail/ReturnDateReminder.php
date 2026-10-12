<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * ReturnDateReminder email
 * ---------------------------------------------------------------------------
 * Sent 24 hours before a booking's return date so customers remember to
 * bring the vehicle back on time — this reduces overdue returns.
 */
class ReturnDateReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        $booking->loadMissing(['user', 'vehicle']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reminder: Return your vehicle tomorrow · Rakz Rental #{$this->booking->id}"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.return-reminder');
    }
}
