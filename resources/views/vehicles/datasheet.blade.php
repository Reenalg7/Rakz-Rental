<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vehicle Datasheet — {{ $vehicle->registration_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 12px; margin: 0; padding: 0; }
        .wrap { padding: 30px 40px; }

        .header { background: #0B0F19; color: #F9FAFB; padding: 18px 30px; margin: -30px -40px 20px; }
        .header .brand { font-size: 18px; font-weight: bold; letter-spacing: 1px; color: #D4AF37; }
        .header .tag { font-size: 10px; color: #9CA3AF; }
        .header .reg-box { float: right; text-align: right; background: #D4AF37; color: #0B0F19; padding: 6px 14px; border-radius: 4px; }
        .header .reg-box .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }
        .header .reg-box .no { font-size: 22px; font-weight: bold; font-family: DejaVu Sans Mono, monospace; }

        h1 { font-size: 22px; margin: 0 0 6px; color: #0B0F19; }
        h2 { font-size: 14px; margin: 20px 0 8px; color: #0B0F19; border-bottom: 2px solid #D4AF37; padding-bottom: 4px; }
        .sub { color: #6B7280; font-size: 13px; margin: 0 0 20px; }

        .photo { width: 100%; height: 220px; background: #F3F4F6; margin-bottom: 20px; text-align: center; overflow: hidden; border-radius: 6px; }
        .photo img { width: 100%; height: 220px; object-fit: cover; }
        .photo .ph { display: inline-block; padding-top: 90px; color: #9CA3AF; font-size: 14px; }

        .specs { width: 100%; border-collapse: collapse; }
        .specs td { padding: 10px 8px; border-bottom: 1px solid #E5E7EB; vertical-align: top; }
        .specs .k { color: #6B7280; width: 40%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .specs .v { font-weight: bold; color: #0B0F19; font-size: 13px; }

        .rate { background: #FFFBEB; border-left: 3px solid #D4AF37; padding: 14px 18px; margin-top: 20px; border-radius: 4px; }
        .rate .label { color: #92400E; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .rate .amount { color: #0B0F19; font-size: 24px; font-weight: bold; margin-top: 2px; }
        .rate .per { color: #6B7280; font-size: 12px; }

        .status { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: bold; }
        .s-available { background: #D1FAE5; color: #065F46; }
        .s-maintenance { background: #FEF3C7; color: #92400E; }
        .s-retired { background: #FEE2E2; color: #991B1B; }

        .notice { background: #F9FAFB; padding: 10px 14px; font-size: 11px; color: #4B5563; margin-top: 20px; border-radius: 4px; }
        .qr { text-align: center; margin-top: 20px; }
        .qr img { width: 100px; height: 100px; background: #FFF; padding: 4px; border-radius: 4px; }
        .qr .cap { color: #6B7280; font-size: 10px; margin-top: 4px; }

        .footer { margin-top: 25px; padding-top: 10px; border-top: 1px solid #E5E7EB; font-size: 10px; color: #6B7280; text-align: center; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div class="reg-box">
            <div class="label">Registration</div>
            <div class="no">{{ $vehicle->registration_no }}</div>
        </div>
        <div class="brand">RAKZ RENTAL</div>
        <div class="tag">Vehicle Datasheet · printed {{ now()->format('M d, Y H:i') }}</div>
    </div>

    <h1>{{ $vehicle->name }}</h1>
    <p class="sub">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }} · {{ $vehicle->category?->name ?? 'Uncategorized' }}</p>

    <div class="photo">
        @if($vehicle->image_url)
            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}">
        @else
            <span class="ph">[ No photo on file ]</span>
        @endif
    </div>

    <h2>Specifications</h2>
    <table class="specs">
        <tr><td class="k">Registration No</td><td class="v" style="font-family: DejaVu Sans Mono, monospace; color: #D4AF37;">{{ $vehicle->registration_no }}</td></tr>
        <tr><td class="k">Make</td><td class="v">{{ $vehicle->make }}</td></tr>
        <tr><td class="k">Model</td><td class="v">{{ $vehicle->model }}</td></tr>
        <tr><td class="k">Year</td><td class="v">{{ $vehicle->year }}</td></tr>
        <tr><td class="k">Category</td><td class="v">{{ $vehicle->category?->name ?? '—' }}</td></tr>
        <tr><td class="k">Seats</td><td class="v">{{ $vehicle->seats }}</td></tr>
        <tr><td class="k">Transmission</td><td class="v">{{ ucfirst($vehicle->transmission) }}</td></tr>
        <tr><td class="k">Fuel</td><td class="v">{{ ucfirst($vehicle->fuel_type) }}</td></tr>
        <tr><td class="k">Status</td><td class="v">
            <span class="status s-{{ $vehicle->status }}">{{ strtoupper($vehicle->status) }}</span>
        </td></tr>
    </table>

    @if($vehicle->description)
        <h2>Description</h2>
        <p style="font-size:11px;line-height:1.6;">{{ $vehicle->description }}</p>
    @endif

    <div class="rate">
        <div class="label">Daily Rental Rate</div>
        <div class="amount">FJD {{ number_format($vehicle->daily_rate, 2) }} <span class="per">/ day</span></div>
    </div>

    @if(!empty($qrDataUri))
        <div class="qr">
            <img src="{{ $qrDataUri }}" alt="QR">
            <div class="cap">Scan to open this vehicle online</div>
        </div>
    @endif

    <div class="notice">
        <strong>Counter instructions:</strong> Clip this sheet to the physical key for
        <strong>{{ $vehicle->registration_no }}</strong>. Verify the registration number on
        the key tag matches the number above before releasing the vehicle to any customer.
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Rakz Rental · Internal vehicle datasheet · Registration-number-first fleet management
    </div>
</div>
</body>
</html>
