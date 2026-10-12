<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking {{ $stage === 'confirmed' ? 'Confirmed' : 'Received' }}</title>
</head>
<body style="margin:0;background:#0B0F19;color:#F9FAFB;font-family:'Inter',Arial,sans-serif;">
    <div style="max-width:600px;margin:0 auto;background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:12px;overflow:hidden;">
        <div style="background:#D4AF37;color:#0B0F19;padding:20px 24px;">
            <h1 style="margin:0;font-size:22px;letter-spacing:-0.01em;">Rakz Rental</h1>
        </div>

        <div style="padding:24px;">
            <h2 style="color:#D4AF37;font-size:20px;margin:0 0 8px;">
                @if($stage === 'confirmed')
                    Your booking is confirmed!
                @else
                    We've received your booking
                @endif
            </h2>
            <p style="color:#9CA3AF;margin:0 0 16px;">
                Hi {{ $booking->user->name }}, thank you for choosing Rakz Rental.
                @if($stage === 'confirmed')
                    Your booking has been confirmed by our team. Please find the details below.
                @else
                    Your booking has been placed and is now awaiting confirmation.
                @endif
            </p>

            <div style="background:#0B0F19;border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:16px;margin-bottom:16px;">
                <table style="width:100%;color:#F9FAFB;font-size:14px;">
                    <tr><td style="color:#9CA3AF;padding:4px 0;">Reference</td><td style="text-align:right;">#{{ $booking->id }}</td></tr>
                    <tr><td style="color:#9CA3AF;padding:4px 0;">Vehicle</td><td style="text-align:right;">{{ $booking->vehicle?->name }}</td></tr>
                    <tr><td style="color:#9CA3AF;padding:4px 0;">Registration No</td><td style="text-align:right;font-family:monospace;color:#D4AF37;">{{ $booking->vehicle_registration_no }}</td></tr>
                    <tr><td style="color:#9CA3AF;padding:4px 0;">Pickup</td><td style="text-align:right;">{{ $booking->pickup_date->format('M d, Y') }}</td></tr>
                    <tr><td style="color:#9CA3AF;padding:4px 0;">Return</td><td style="text-align:right;">{{ $booking->return_date->format('M d, Y') }}</td></tr>
                    <tr><td style="color:#9CA3AF;padding:4px 0;">Days</td><td style="text-align:right;">{{ $booking->total_days }}</td></tr>
                    <tr><td style="color:#9CA3AF;padding:4px 0;border-top:1px solid rgba(255,255,255,0.1);">Total</td>
                        <td style="text-align:right;font-weight:bold;color:#D4AF37;border-top:1px solid rgba(255,255,255,0.1);">FJD {{ number_format($booking->total_amount, 2) }}</td></tr>
                </table>
            </div>

            <p style="color:#9CA3AF;font-size:13px;line-height:1.6;margin:0 0 20px;">
                Please bring a valid driving license and your NIC when picking up the vehicle.
                If you have any questions, reply to this email or contact our team.
            </p>

            <a href="{{ config('app.url') }}/my/bookings/{{ $booking->id }}"
               style="display:inline-block;background:#D4AF37;color:#0B0F19;text-decoration:none;padding:10px 20px;border-radius:6px;font-weight:600;">
                View booking →
            </a>
        </div>

        <div style="padding:16px 24px;border-top:1px solid rgba(255,255,255,0.08);color:#6B7280;font-size:12px;">
            &copy; {{ date('Y') }} Rakz Rental. This is an automated message from our booking system.
        </div>
    </div>
</body>
</html>
