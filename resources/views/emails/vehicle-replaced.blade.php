<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vehicle Replaced</title>
</head>
<body style="margin:0;background:#FAFAFA;color:#14232F;font-family:'Inter',Arial,sans-serif;">
    <div style="max-width:600px;margin:0 auto;background:#FFFFFF;border:1px solid #D4E2EB;border-radius:8px;overflow:hidden;">
        <div style="background:#005691;color:#FFFFFF;padding:20px 24px;">
            <h1 style="margin:0;font-size:22px;">Rakz Rental</h1>
        </div>

        <div style="padding:24px;">
            <h2 style="color:#004A7C;font-size:20px;margin:0 0 8px;">Your vehicle has been replaced</h2>
            <p style="color:#506576;margin:0 0 16px;">
                Hi {{ $booking->user->name }}, we had to swap the vehicle on your booking
                <strong style="color:#14232F;">#{{ $booking->id }}</strong>.
                Your booking dates and total amount remain unchanged.
            </p>

            <div style="background:#E8F1F5;border:1px solid #D4E2EB;border-radius:8px;padding:16px;margin-bottom:16px;">
                <table style="width:100%;color:#14232F;font-size:14px;">
                    <tr>
                        <td style="color:#506576;padding:6px 0;">Original Vehicle</td>
                        <td style="text-align:right;font-family:monospace;">
                            {{ $originalVehicle->registration_no }} <span style="color:#506576;">({{ $originalVehicle->name }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#506576;padding:6px 0;">New Vehicle</td>
                        <td style="text-align:right;font-family:monospace;color:#004A7C;font-weight:bold;">
                            {{ $replacementVehicle->registration_no }}
                            <span style="color:#14232F;font-weight:normal;">({{ $replacementVehicle->name }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#506576;padding:6px 0;">Pickup</td>
                        <td style="text-align:right;">{{ $booking->pickup_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color:#506576;padding:6px 0;">Return</td>
                        <td style="text-align:right;">{{ $booking->return_date->format('M d, Y') }}</td>
                    </tr>
                    @if($reason)
                    <tr>
                        <td style="color:#506576;padding:6px 0;vertical-align:top;">Reason</td>
                        <td style="text-align:right;color:#14232F;">{{ $reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="color:#506576;font-size:13px;line-height:1.6;margin:0 0 20px;">
                We apologise for any inconvenience. If you have questions about this change,
                please reply to this email.
            </p>

            <a href="{{ config('app.url') }}/my/bookings/{{ $booking->id }}"
               style="display:inline-block;background:#005691;color:#FFFFFF;text-decoration:none;padding:10px 20px;border-radius:6px;font-weight:600;">
                View updated booking
            </a>
        </div>

        <div style="padding:16px 24px;border-top:1px solid #D4E2EB;color:#506576;font-size:12px;">
            &copy; {{ date('Y') }} Rakz Rental. Automated notification.
        </div>
    </div>
</body>
</html>
