<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Booking Confirmed</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #D2C1B6;
            min-height: 100vh;
        }

        /* Navbar */

        .navbar {
            background-color: #1B3C53;
        }

        .brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .brand:hover {
            color: white;
        }

        /* Main */

        .confirmation-section {
            padding: 50px 15px;
        }

        .confirmation-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background-color: white;
        }

        /* Success Icon */

        .success-icon {
            width: 70px;
            height: 70px;
            background-color: #456882;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            font-size: 36px;
            font-weight: bold;
        }

        /* Heading */

        .title {
            color: #1B3C53;
            font-size: 32px;
            font-weight: 700;
        }

        .subtitle {
            color: #6c757d;
            font-size: 15px;
        }

        /* Booking Reference */

        .booking-reference {
            background-color: #f7f4f2;
            border-radius: 12px;
            padding: 14px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .reference-label {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .reference-number {
            color: #1B3C53;
            font-size: 18px;
            font-weight: 700;
        }

        /* Vehicle */

        .vehicle-box {
            background-color: #f7f4f2;
            border-radius: 15px;
            padding: 22px;
            text-align: left;
            margin-bottom: 25px;
        }

        .vehicle-name {
            color: #234C6A;
            font-size: 25px;
            font-weight: 700;
        }

        .vehicle-category {
            color: #6c757d;
        }

        /* Details */

        .details-box {
            border: 1px solid #e4e4e4;
            border-radius: 15px;
            padding: 20px;
            text-align: left;
        }

        .detail-label {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .detail-value {
            color: #1B3C53;
            font-weight: 600;
        }

        /* Total */

        .total-section {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
        }

        .total-label {
            color: #6c757d;
            margin-bottom: 5px;
        }

        .total {
            color: #234C6A;
            font-size: 30px;
            font-weight: 700;
        }

        /* Status */

        .status {
            background-color: #456882;
            color: white;
            font-size: 14px;
            padding: 9px 18px;
            border-radius: 20px;
        }

        /* Buttons */

        .back-btn {
            background-color: #234C6A;
            color: white;
            border: none;
            border-radius: 9px;
            padding: 11px 20px;
            font-weight: 600;
        }

        .back-btn:hover {
            background-color: #1B3C53;
            color: white;
        }

        .history-btn {
            border-radius: 9px;
            padding: 10px 20px;
            font-weight: 600;
            color: #456882;
            border-color: #456882;
        }

        .history-btn:hover {
            background-color: #456882;
            border-color: #456882;
            color: white;
        }

        /* Responsive */

        @media (max-width: 576px) {

            .confirmation-section {
                padding: 25px 15px;
            }

            .title {
                font-size: 27px;
            }

            .vehicle-name {
                font-size: 22px;
            }

            .button-container {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

        }

    </style>

</head>


<body>

    <!-- Navigation -->

    <nav class="navbar py-3">

        <div class="container">

            <a
                href="{{ route('vehicles.index') }}"
                class="brand"
            >
                Rakz Rental
            </a>

        </div>

    </nav>


    <!-- Confirmation Section -->

    <div class="container confirmation-section">

        <div class="row justify-content-center">

            <div class="col-lg-7 col-md-9">

                <div class="card confirmation-card shadow">

                    <div class="card-body p-4 p-md-5">

                        <!-- Success -->

                        <div class="text-center">

                            <div class="success-icon">
                                ✓
                            </div>

                            <h1 class="title">
                                Booking Confirmed
                            </h1>

                            <p class="subtitle">
                                Your vehicle has been successfully reserved.
                            </p>

                        </div>


                        <!-- Booking Reference -->

                        <div class="booking-reference text-center">

                            <div class="reference-label">
                                Booking Reference
                            </div>

                            <div class="reference-number">
                                #{{ $booking->id }}
                            </div>

                        </div>


                        <!-- Vehicle -->

                        <div class="vehicle-box">

                            <div class="vehicle-name">
                                {{ $booking->vehicle->name }}
                            </div>

                            <div class="vehicle-category mb-2">
                                {{ $booking->vehicle->category }}
                            </div>

                            <div class="text-muted">

                                Daily Rate:
                                <strong>
                                    ${{ number_format($booking->vehicle->daily_rate, 2) }}
                                </strong>

                            </div>

                        </div>


                        <!-- Customer -->

                        <div class="details-box">

                            <div class="row g-4">

                                <!-- Customer -->

                                <div class="col-md-6">

                                    <div class="detail-label">
                                        Customer
                                    </div>

                                    <div class="detail-value">
                                        {{ $booking->customer_name }}
                                    </div>

                                </div>


                                <!-- Registration -->

                                <div class="col-md-6">

                                    <div class="detail-label">
                                        Vehicle Registration
                                    </div>

                                    <div class="detail-value">
                                        {{ $booking->vehicle->registration }}
                                    </div>

                                </div>


                                <!-- Pickup -->

                                <div class="col-md-6">

                                    <div class="detail-label">
                                        Pickup Date
                                    </div>

                                    <div class="detail-value">
                                        {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}
                                    </div>

                                </div>


                                <!-- Return -->

                                <div class="col-md-6">

                                    <div class="detail-label">
                                        Return Date
                                    </div>

                                    <div class="detail-value">
                                        {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Total -->

                        <div class="total-section text-center">

                            <div class="total-label">
                                Total Rental Cost
                            </div>

                            <div class="total">
                                ${{ number_format($booking->total_amount, 2) }}
                            </div>

                            <span class="badge status">
                                {{ ucfirst($booking->status) }}
                            </span>

                        </div>


                        <!-- Buttons -->

                        <div class="button-container text-center mt-4">

                            <a
                                href="{{ route('vehicles.index') }}"
                                class="btn back-btn me-2"
                            >
                                Browse Vehicles
                            </a>

                            <a
                                href="{{ route('bookings.index') }}"
                                class="btn btn-outline-secondary history-btn"
                            >
                                Booking History
                            </a>

                        </div>


                        <!-- Message -->

                        <p class="text-center text-muted small mt-4 mb-0">

                            Thank you for choosing Rakz Rental.
                            Your booking details have been recorded.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>