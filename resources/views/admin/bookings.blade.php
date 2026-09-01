<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rakz Rental - Admin Bookings</title>

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

        .confirm-btn {
            background-color: #456882;
            color: white;
            border: none;
        }

        .confirm-btn:hover {
            background-color: #234C6A;
            color: white;
        }

        .cancel-btn {
            background-color: #1B3C53;
            color: white;
            border: none;
        }

        .cancel-btn:hover {
            background-color: #234C6A;
            color: white;
        }

        .pending {
            background-color: #D2C1B6;
            color: #1B3C53;
        }

        .confirmed {
            background-color: #456882;
            color: white;
        }

        .cancelled {
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
                Customer View
            </a>

        </div>

    </nav>


    <!-- Main Content -->

    <div class="container py-5">

        <div class="text-center mb-5">

            <h1 class="page-title fw-bold">
                Admin Dashboard
            </h1>

            <p class="text-muted">
                Manage customer rental bookings
            </p>

        </div>

        <!-- Dashboard Statistics -->

<div class="row mb-5">

    <!-- Total Bookings -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm booking-card text-center">

            <div class="card-body">

                <h6 class="text-muted">
                    Total Bookings
                </h6>

                <h2 class="fw-bold" style="color: #1B3C53;">
                    {{ $totalBookings }}
                </h2>

            </div>

        </div>

    </div>


    <!-- Pending -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm booking-card text-center">

            <div class="card-body">

                <h6 class="text-muted">
                    Pending
                </h6>

                <h2 class="fw-bold" style="color: #456882;">
                    {{ $pendingBookings }}
                </h2>

            </div>

        </div>

    </div>


    <!-- Confirmed -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm booking-card text-center">

            <div class="card-body">

                <h6 class="text-muted">
                    Confirmed
                </h6>

                <h2 class="fw-bold" style="color: #234C6A;">
                    {{ $confirmedBookings }}
                </h2>

            </div>

        </div>

    </div>


    <!-- Cancelled -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm booking-card text-center">

            <div class="card-body">

                <h6 class="text-muted">
                    Cancelled
                </h6>

                <h2 class="fw-bold" style="color: #1B3C53;">
                    {{ $cancelledBookings }}
                </h2>

            </div>

        </div>

    </div>

</div>


        <!-- Success Message -->

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif


        <!-- Bookings -->

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
                                    <strong>Customer:</strong>
                                    {{ $booking->customer_name }}
                                </p>


                                <p>
                                    <strong>Pickup:</strong>
                                    {{ $booking->pickup_date }}
                                </p>


                                <p>
                                    <strong>Return:</strong>
                                    {{ $booking->return_date }}
                                </p>


                                <p>
                                    <strong>Total:</strong>
                                    ${{ number_format($booking->total_amount, 2) }}
                                </p>


                                <p>

                                    <strong>Status:</strong>

                                    <span class="badge
                                        @if($booking->status == 'pending')
                                            pending
                                        @elseif($booking->status == 'confirmed')
                                            confirmed
                                        @else
                                            cancelled
                                        @endif
                                    ">

                                        {{ ucfirst($booking->status) }}

                                    </span>

                                </p>


                                @if($booking->status == 'pending')

                                    <div class="d-flex gap-2">

                                        <!-- Confirm -->

                                        <form
                                            method="POST"
                                            action="{{ route('admin.bookings.status', $booking) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="confirmed"
                                            >

                                            <button
                                                type="submit"
                                                class="btn confirm-btn"
                                            >
                                                Confirm Booking
                                            </button>

                                        </form>


                                        <!-- Cancel -->

                                        <form
                                            method="POST"
                                            action="{{ route('admin.bookings.status', $booking) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="cancelled"
                                            >

                                            <button
                                                type="submit"
                                                class="btn cancel-btn"
                                            >
                                                Cancel Booking
                                            </button>

                                        </form>

                                    </div>

                                @endif

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

    </div>

</body>

</html>