<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rakz Rental Invoice #{{ $booking->id }}</title>
    <style>
        /* DomPDF supports a subset of CSS. Keep it inline-friendly. */
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 12px; margin: 0; padding: 0; }
        .wrap { padding: 30px 40px; }

        .header { background: #0B0F19; color: #F9FAFB; padding: 20px 30px; margin: -30px -40px 20px; }
        .header .brand { font-size: 20px; font-weight: bold; letter-spacing: 1px; color: #D4AF37; }
        .header .tag { font-size: 10px; color: #9CA3AF; }
        .header .inv-no { float: right; text-align: right; }
        .header .inv-no .num { font-size: 18px; font-weight: bold; }

        h2 { font-size: 14px; margin: 20px 0 8px; color: #0B0F19; border-bottom: 2px solid #D4AF37; padding-bottom: 4px; }

        .meta { width: 100%; margin-bottom: 20px; }
        .meta td { vertical-align: top; width: 50%; }
        .meta .label { color: #6B7280; font-size: 10px; text-transform: uppercase; }
        .meta .value { font-weight: bold; }

        table.lines { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.lines th { background: #F3F4F6; text-align: left; padding: 8px; border-bottom: 2px solid #D4AF37; font-size: 11px; }
        table.lines td { padding: 8px; border-bottom: 1px solid #E5E7EB; }
        table.lines td.r, table.lines th.r { text-align: right; }

        .totals { width: 50%; margin-left: 50%; margin-top: 10px; }
        .totals td { padding: 4px 8px; }
        .totals .label { color: #6B7280; }
        .totals .v { text-align: right; }
        .totals .grand td { border-top: 2px solid #0B0F19; font-weight: bold; font-size: 14px; padding-top: 8px; }
        .totals .grand .v { color: #D4AF37; }

        .mono { font-family: DejaVu Sans Mono, monospace; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .b-paid { background: #D1FAE5; color: #065F46; }
        .b-unpaid { background: #FEF3C7; color: #92400E; }
        .b-refunded { background: #E5E7EB; color: #374151; }

        .notice { background: #FEF3C7; border-left: 3px solid #D97706; padding: 8px 10px; font-size: 11px; margin: 10px 0; color: #92400E; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #E5E7EB; font-size: 10px; color: #6B7280; text-align: center; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div class="inv-no">
            <div class="tag">INVOICE</div>
            <div class="num">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="tag">{{ $booking->created_at->format('M d, Y') }}</div>
            @if(!empty($qrDataUri))
                <img src="{{ $qrDataUri }}" alt="QR"
                     style="width:70px;height:70px;background:#FFF;padding:3px;border-radius:4px;margin-top:6px;">
            @endif
        </div>
        <div class="brand">RAKZ RENTAL</div>
        <div class="tag">Luxury &amp; Performance Car Rental</div>
    </div>

    <table class="meta">
        <tr>
            <td>
                <div class="label">Billed to</div>
                <div class="value">{{ $booking->user->name }}</div>
                <div>{{ $booking->user->email }}</div>
                @if($booking->user->phone)<div>{{ $booking->user->phone }}</div>@endif
                @if($booking->user->address)<div>{{ $booking->user->address }}</div>@endif
                @if($booking->user->nic)<div class="label" style="margin-top:4px;">NIC: {{ $booking->user->nic }}</div>@endif
            </td>
            <td>
                <div class="label">From</div>
                <div class="value">Rakz Rental</div>
                <div>No. 1, Automobile Avenue, Colombo</div>
                <div>support@rakzrental.com</div>
                <div class="label" style="margin-top:4px;">Payment status</div>
                @php $bs = $booking->billing?->status ?? 'unpaid'; @endphp
                <div>
                    @if($bs === 'paid')
                        <span class="badge b-paid">PAID</span>
                        @if($booking->billing?->paid_at) on {{ $booking->billing->paid_at->format('M d, Y') }} @endif
                    @elseif($bs === 'refunded')
                        <span class="badge b-refunded">REFUNDED</span>
                    @else
                        <span class="badge b-unpaid">UNPAID</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    @if($booking->wasReplaced())
        <div class="notice">
            <strong>Note:</strong> Vehicle on this booking was replaced from
            <span class="mono">{{ $booking->original_vehicle_registration_no }}</span>
            to <span class="mono">{{ $booking->vehicle_registration_no }}</span>.
            The daily rate and total shown below are the originally agreed amounts.
        </div>
    @endif

    <h2>Rental details</h2>
    <table class="lines">
        <thead>
            <tr>
                <th>Vehicle</th>
                <th>Registration No</th>
                <th>Period</th>
                <th class="r">Days</th>
                <th class="r">Daily Rate</th>
                <th class="r">Amount (FJD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $booking->vehicle?->name }}</strong><br>
                    <span style="color:#6B7280;">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }} · {{ $booking->vehicle?->year }}</span><br>
                    <span style="color:#6B7280;">{{ $booking->vehicle?->category?->name }}</span>
                </td>
                <td class="mono"><strong>{{ $booking->vehicle_registration_no }}</strong></td>
                <td>
                    {{ $booking->pickup_date->format('M d, Y') }}<br>
                    &nbsp;→ {{ $booking->return_date->format('M d, Y') }}
                </td>
                <td class="r">{{ $booking->total_days }}</td>
                <td class="r">{{ number_format($booking->daily_rate_snapshot, 2) }}</td>
                <td class="r"><strong>{{ number_format($booking->total_amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr><td class="label">Subtotal</td><td class="v">FJD {{ number_format($booking->total_amount, 2) }}</td></tr>
        <tr><td class="label">Tax</td><td class="v">FJD 0.00</td></tr>
        <tr class="grand"><td>Total due</td><td class="v">FJD {{ number_format($booking->total_amount, 2) }}</td></tr>
    </table>

    @if($booking->notes)
        <h2>Customer notes</h2>
        <div style="font-size:11px;">{{ $booking->notes }}</div>
    @endif

    @if($booking->replacements->isNotEmpty())
        <h2>Replacement audit</h2>
        <table class="lines">
            <thead><tr><th>Date</th><th>From</th><th>To</th><th>Reason</th></tr></thead>
            <tbody>
                @foreach($booking->replacements as $r)
                    <tr>
                        <td>{{ $r->created_at->format('M d, Y H:i') }}</td>
                        <td class="mono">{{ $r->original_registration_no }}</td>
                        <td class="mono"><strong>{{ $r->replacement_registration_no }}</strong></td>
                        <td>{{ $r->reason ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Thank you for choosing Rakz Rental. For questions about this invoice,
        contact support@rakzrental.com quoting reference <strong>#{{ $booking->id }}</strong>.
        @if(!empty($bookingUrl))
            <div style="margin-top:4px;">Scan the QR above or visit <span class="mono">{{ $bookingUrl }}</span> to verify this booking at pickup.</div>
        @endif
    </div>
</div>
</body>
</html>
