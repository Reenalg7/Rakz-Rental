<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vehicle Replaced</title>
</head>
<body style="margin:0;background:#0B0F19;color:#F9FAFB;font-family:'Inter',Arial,sans-serif;">
    <div style="max-width:600px;margin:0 auto;background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:12px;overflow:hidden;">
        <div style="background:#D4AF37;color:#0B0F19;padding:20px 24px;">
            <h1 style="margin:0;font-size:22px;">Rakz Rental</h1>
        </div>

        <div style="padding:24px;">
            <h2 style="color:#D4AF37;font-size:20px;margin:0 0 8px;">Your vehicle has been replaced</h2>
            <p style="color:#9CA3AF;margin:0 0 16px;">
                Hi {{ $booking->user->name }}, we had to swap the vehicle on your booking
                <strong style="color:#F9FAFB;">#{{ $booking->id }}</strong>.
                Your booking dates and total amount remain unchanged.
            </p>

            <div style="background:#0B0F19;border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:16px;margin-bottom:16px;">
                <table style="width:100%;color:#F9FAFB;font-size:14px;">
                    <tr>
                        <td style="color:#9CA3AF;padding:6px 0;">Original Vehicle</td>
                        <td style="text-align:right;font-family:monospace;">
                            {{ $originalVehicle->registration_no }} <span style="color:#9CA3AF;">({{ $originalVehicle->name }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#9CA3AF;padding:6px 0;">New Vehicle</td>
                        <td style="text-align:right;font-family:monospace;color:#D4AF37;font-weight:bold;">
                            {{ $replacementVehicle->registration_no }}
                            <span style="color:#F9FAFB;font-weight:normal;">({{ $replacementVehicle->name }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#9CA3AF;padding:6px 0;">Pickup</td>
                        <td style="text-align:right;">{{ $booking->pickup_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9CA3AF;padding:6px 0;">Return</td>
                        <td style="text-align:right;">{{ $booking->return_date->format('M d, Y') }}</td>
                    </tr>
                    @if($reason)
                    <tr>
                        <td style="color:#9CA3AF;padding:6px 0;vertical-align:top;">Reason</td>
                        <td style="text-align:right;color:#F9FAFB;">{{ $reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="color:#9CA3AF;font-size:13px;line-height:1.6;margin:0 0 20px;">
                We apologise for any inconvenience. If you have questions about this change,
                please reply to this email.
            </p>

            <a href="{{ config('app.url') }}/my/bookings/{{ $booking->id }}"
               style="display:inline-block;background:#D4AF37;color:#0B0F19;text-decoration:none;padding:10px 20px;border-radius:6px;font-weight:600;">
                View updated booking →
            </a>
        </div>

        <div style="padding:16px 24px;border-top:1px solid rgba(255,255,255,0.08);color:#6B7280;font-size:12px;">
            &copy; {{ date('Y') }} Rakz Rental. Automated notification.
        </div>
    </div>
</body>
</html>
