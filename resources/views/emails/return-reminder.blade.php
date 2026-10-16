<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Return Reminder</title>
</head>
<body style="margin:0;background:#FAFAFA;color:#14232F;font-family:'Inter',Arial,sans-serif;">
    <div style="max-width:600px;margin:0 auto;background:#FFFFFF;border:1px solid #D4E2EB;border-radius:8px;overflow:hidden;">
        <div style="background:#005691;color:#FFFFFF;padding:20px 24px;">
            <h1 style="margin:0;font-size:22px;">Rakz Rental</h1>
        </div>

        <div style="padding:24px;">
            <h2 style="color:#004A7C;font-size:20px;margin:0 0 8px;">
                Reminder: return your vehicle tomorrow
            </h2>
            <p style="color:#506576;margin:0 0 16px;">
                Hi {{ $booking->user->name }}, this is a friendly reminder that your Rakz Rental booking
                <strong style="color:#14232F;">#{{ $booking->id }}</strong> is due back
                <strong style="color:#004A7C;">tomorrow, {{ $booking->return_date->format('l, M d, Y') }}</strong>.
            </p>

            <div style="background:#E8F1F5;border:1px solid #D4E2EB;border-radius:8px;padding:16px;margin-bottom:16px;">
                <table style="width:100%;color:#14232F;font-size:14px;">
                    <tr><td style="color:#506576;padding:4px 0;">Vehicle</td><td style="text-align:right;">{{ $booking->vehicle?->name }}</td></tr>
                    <tr><td style="color:#506576;padding:4px 0;">Registration No</td><td style="text-align:right;font-family:monospace;color:#004A7C;">{{ $booking->vehicle_registration_no }}</td></tr>
                    <tr><td style="color:#506576;padding:4px 0;">Return by</td><td style="text-align:right;font-weight:bold;">{{ $booking->return_date->format('M d, Y') }}</td></tr>
                </table>
            </div>

            <p style="color:#506576;font-size:13px;line-height:1.6;margin:0 0 20px;">
                Please return the vehicle to our office before close of business.
                If you need to extend your booking, reply to this email as soon as possible —
                we'll do our best to accommodate your request.
            </p>

            <a href="{{ config('app.url') }}/my/bookings/{{ $booking->id }}"
               style="display:inline-block;background:#005691;color:#FFFFFF;text-decoration:none;padding:10px 20px;border-radius:6px;font-weight:600;">
                View booking
            </a>
        </div>

        <div style="padding:16px 24px;border-top:1px solid #D4E2EB;color:#506576;font-size:12px;">
            &copy; {{ date('Y') }} Rakz Rental. Automated reminder — safe to ignore if the vehicle is already returned.
        </div>
    </div>
</body>
</html>
