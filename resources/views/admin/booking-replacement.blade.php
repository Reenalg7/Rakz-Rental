<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Replace Vehicle - Rakz Rental</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7f9;
            color: #1B3C53;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #456882;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #1B3C53;
        }

        .booking-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info-box {
            background: #f5f7f9;
            padding: 16px;
            border-radius: 9px;
        }

        .info-label {
            display: block;
            font-size: 13px;
            color: #456882;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #1B3C53;
        }

        .current-vehicle {
            border-left: 5px solid #456882;
        }

        .vehicles {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .vehicle-option {
            position: relative;
        }

        .vehicle-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .vehicle-card {
            display: block;
            border: 2px solid #e1e5e8;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: 0.2s ease;
            height: 100%;
        }

        .vehicle-card:hover {
            border-color: #456882;
            transform: translateY(-2px);
        }

        .vehicle-option input[type="radio"]:checked + .vehicle-card {
            border-color: #1B3C53;
            background: #f1f5f7;
            box-shadow: 0 0 0 2px rgba(27, 60, 83, 0.08);
        }

        .vehicle-registration {
            font-size: 20px;
            font-weight: bold;
            color: #1B3C53;
            margin-bottom: 8px;
        }

        .vehicle-name {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .vehicle-details {
            color: #456882;
            font-size: 14px;
            line-height: 1.7;
        }

        .vehicle-price {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #e1e5e8;
            font-size: 18px;
            font-weight: bold;
            color: #1B3C53;
        }

        .selected-text {
            margin-top: 10px;
            font-size: 13px;
            color: #456882;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary {
            background: #1B3C53;
            color: white;
        }

        .btn-primary:hover {
            background: #234C6A;
        }

        .btn-secondary {
            background: #D2C1B6;
            color: #1B3C53;
        }

        .btn-secondary:hover {
            background: #c4b1a5;
        }

        .alert {
            padding: 15px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #f8d7da;
            color: #842029;
        }

        .alert-warning {
            background: #fff3cd;
            color: #664d03;
        }

        .no-vehicles {
            padding: 25px;
            text-align: center;
            background: #f5f7f9;
            border-radius: 10px;
            color: #456882;
        }

        @media (max-width: 900px) {
            .vehicles {
                grid-template-columns: repeat(2, 1fr);
            }

            .booking-info {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .vehicles,
            .booking-info {
                grid-template-columns: 1fr;
            }

            .container {
                margin: 20px auto;
                padding: 15px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Page Header -->
    <div class="header">
        <h1>Replace Vehicle</h1>

        <p>
            Select another available vehicle for this customer's confirmed booking.
        </p>
    </div>


    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif


    <!-- Booking Information -->
    <div class="card current-vehicle">

        <h2>Booking Information</h2>

        <div class="booking-info">

            <div class="info-box">
                <span class="info-label">
                    Booking ID
                </span>

                <span class="info-value">
                    #{{ $booking->id }}
                </span>
            </div>


            <div class="info-box">
                <span class="info-label">
                    Customer
                </span>

                <span class="info-value">
                    {{ $booking->user->name ?? $booking->customer_name }}
                </span>
            </div>


            <div class="info-box">
                <span class="info-label">
                    Current Vehicle
                </span>

                <span class="info-value">
                    {{ $booking->vehicle->registration ?? 'N/A' }}
                    -
                    {{ $booking->vehicle->name ?? 'N/A' }}
                </span>
            </div>


            <div class="info-box">
                <span class="info-label">
                    Pickup Date
                </span>

                <span class="info-value">
                    {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}
                </span>
            </div>


            <div class="info-box">
                <span class="info-label">
                    Return Date
                </span>

                <span class="info-value">
                    {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}
                </span>
            </div>


            <div class="info-box">
                <span class="info-label">
                    Booking Status
                </span>

                <span class="info-value">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

        </div>

    </div>


    <!-- Replacement Vehicles -->
    <div class="card">

        <h2>Available Replacement Vehicles</h2>

        @if($replacementVehicles->count() > 0)

            <form
                method="POST"
                action="{{ route('admin.bookings.replace', $booking) }}"
            >

                @csrf


                <div class="vehicles">

                    @foreach($replacementVehicles as $vehicle)

                        <div class="vehicle-option">

                            <input
                                type="radio"
                                id="vehicle-{{ $vehicle->id }}"
                                name="replacement_vehicle_id"
                                value="{{ $vehicle->id }}"
                                required
                            >

                            <label
                                for="vehicle-{{ $vehicle->id }}"
                                class="vehicle-card"
                            >

                                <div class="vehicle-registration">
                                    {{ $vehicle->registration }}
                                </div>

                                <div class="vehicle-name">
                                    {{ $vehicle->name }}
                                </div>

                                <div class="vehicle-details">

                                    <div>
                                        <strong>Category:</strong>
                                        {{ $vehicle->category }}
                                    </div>

                                    <div>
                                        <strong>Status:</strong>
                                        Available
                                    </div>

                                </div>

                                <div class="vehicle-price">
                                    ${{ number_format($vehicle->daily_rate, 2) }}/day
                                </div>

                                <div class="selected-text">
                                    Select this vehicle
                                </div>

                            </label>

                        </div>

                    @endforeach

                </div>


                <!-- Buttons -->
                <div class="actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                        onclick="return confirm('Are you sure you want to replace the vehicle for this booking?')"
                    >
                        Replace Vehicle
                    </button>

                    <a
                        href="{{ route('admin.bookings') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        @else

            <div class="no-vehicles">

                <h3>No Replacement Vehicles Available</h3>

                <p>
                    There are currently no available vehicles that can replace
                    the current vehicle for these booking dates.
                </p>

            </div>

        @endif

    </div>

</div>

</body>
</html>