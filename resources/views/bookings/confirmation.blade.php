<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rakz Rental - Confirmation</title>

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

        .confirmation-card {
            border: none;
            border-radius: 12px;
        }

        .title {
            color: #1B3C53;
        }

        .vehicle-name {
            color: #234C6A;
        }

        .total {
            color: #234C6A;
            font-size: 24px;
            font-weight: bold;
        }

        .status {
            background-color: #456882;
            color: white;
        }

        .back-btn {
            background-color: #234C6A;
            color: white;
            border: none;
        }

        .back-btn:hover {
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

        </div>

    </nav>


    <!-- Confirmation -->

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="card confirmation-card shadow">

                    <div class="card-body p-5 text-center">

                        <h1 class="title fw-bold">
                            Booking Confirmed
                        </h1>

                        <p class="text-muted mb-4">
                            Your rental booking has been successfully created.
                        </p>


                        <hr>


                        <h3 class="vehicle-name fw-bold mt-4">
                            {{ $booking->vehicle->name }}
                        </h3>

                        <p>
                          Customer:
                         <strong>{{ $booking->customer_name }}</strong>
                         </p>

                        <p>
                            Category:
                            <strong>
                                {{ $booking->vehicle->category }}
                            </strong>
                        </p>


                        <div class="row mt-4">

                            <div class="col-md-6">

                                <p class="text-muted mb-1">
                                    Pickup Date
                                </p>

                                <strong>
                                    {{ $booking->pickup_date }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <p class="text-muted mb-1">
                                    Return Date
                                </p>

                                <strong>
                                    {{ $booking->return_date }}
                                </strong>

                            </div>

                        </div>


                        <hr>


                        <p class="text-muted mb-1">
                            Total Rental Cost
                        </p>

                        <p class="total">
                            ${{ number_format($booking->total_amount, 2) }}
                        </p>


                        <span class="badge status p-2">
                            {{ ucfirst($booking->status) }}
                        </span>


                        <div class="mt-4">

                            <a
                                href="{{ route('vehicles.index') }}"
                                class="btn back-btn"
                            >
                                Back to Vehicles
                            </a>

                            <a
                                href="{{ route('bookings.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Booking History
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>