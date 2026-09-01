<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rakz Rental - Booking History</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #D2C1B6;
        }

        .navbar {
            background-color: #1B3C53;
        }

        .brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .page-title {
            color: #1B3C53;
        }

        .booking-card {
            border: none;
            border-radius: 12px;
        }

        .vehicle-name {
            color: #234C6A;
        }

        .total {
            color: #234C6A;
            font-weight: bold;
        }

        .status {
            background-color: #456882;
            color: white;
        }

        .vehicle-btn {
            background-color: #234C6A;
            color: white;
            border: none;
        }

        .vehicle-btn:hover {
            background-color: #1B3C53;
            color: white;
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

            <a
                href="{{ route('vehicles.index') }}"
                class="btn btn-outline-light"
            >
                Vehicles
            </a>

        </div>

    </nav>


    <!-- Booking History -->

    <div class="container py-5">

        <div class="text-center mb-5">

            <h1 class="page-title fw-bold">
                Booking History
            </h1>

            <p class="text-muted">
                View your previous rental bookings
            </p>

        </div>


        @if($bookings->count() > 0)

            <div class="row">

                @foreach($bookings as $booking)

                    <div class="col-md-6 mb-4">

                        <div class="card booking-card shadow-sm">

                            <div class="card-body p-4">

                                <h3 class="vehicle-name fw-bold">
                                    {{ $booking->vehicle->name }}
                                </h3>

                                <p class="text-muted">
                                    {{ $booking->vehicle->category }}
                                </p>

                                <p>
                                     Customer:
                                       <strong>{{ $booking->customer_name }}</strong>
                                </p>

                                <div class="row">

                                    <div class="col-6">

                                        <p class="mb-1 text-muted">
                                            Pickup
                                        </p>

                                        <strong>
                                            {{ $booking->pickup_date }}
                                        </strong>

                                    </div>


                                    <div class="col-6">

                                        <p class="mb-1 text-muted">
                                            Return
                                        </p>

                                        <strong>
                                            {{ $booking->return_date }}
                                        </strong>

                                    </div>

                                </div>


                                <hr>


                                <p class="mb-1 text-muted">
                                    Total Amount
                                </p>

                                <p class="total fs-5">
                                    ${{ number_format($booking->total_amount, 2) }}
                                </p>


                                <span class="badge status">
                                    {{ ucfirst($booking->status) }}
                                </span>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="text-center">

                <p>
                    No bookings found.
                </p>

            </div>

        @endif


        <div class="text-center mt-4">

            <a
                href="{{ route('vehicles.index') }}"
                class="btn vehicle-btn"
            >
                Browse Vehicles
            </a>

        </div>

    </div>

</body>

</html>