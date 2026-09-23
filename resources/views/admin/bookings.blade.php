<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Manage Bookings</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background-color: #D2C1B6;
            font-family: Arial, sans-serif;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 230px;
            height: 100vh;
            background-color: #1B3C53;
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
        }


        .brand {
            color: white;
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 40px;
        }


        .brand:hover {
            color: white;
        }


        .nav-link {
            color: white;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            text-decoration: none;
            display: block;
            font-size: 16px;
        }


        .nav-link:hover {
            background-color: #234C6A;
            color: white;
        }


        .nav-link.active {
            background-color: #456882;
            color: white;
        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .main-content {
            margin-left: 230px;
            padding: 40px;
            min-height: 100vh;
        }


        .page-title {
            color: #1B3C53;
            font-weight: bold;
        }


        /* =========================
           BOOKING CARD
        ========================= */

        .booking-card {
            border: none;
            border-radius: 15px;
            margin-bottom: 20px;
        }


        .booking-card:hover {
            transform: translateY(-2px);
            transition: 0.2s;
        }


        .vehicle-name {
            color: #1B3C53;
            font-size: 20px;
            font-weight: bold;
        }


        .booking-label {
            color: #456882;
            font-size: 13px;
            font-weight: bold;
        }


        .booking-value {
            color: #1B3C53;
        }


        /* =========================
           STATUS
        ========================= */

        .status-confirmed {
            background-color: #456882;
            color: white;
        }


        .status-cancelled {
            background-color: #1B3C53;
            color: white;
        }


        /* =========================
           REPLACEMENT VEHICLE
        ========================= */

        .replacement-info {
            background-color: #f1f5f7;
            border-left: 4px solid #456882;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 15px;
        }


        .replacement-label {
            color: #456882;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }


        .replacement-value {
            color: #1B3C53;
            font-weight: bold;
        }


        .btn-replace {
            background-color: #234C6A;
            color: white;
            border: none;
        }


        .btn-replace:hover {
            background-color: #1B3C53;
            color: white;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .sidebar {
                width: 200px;
            }


            .main-content {
                margin-left: 200px;
                padding: 25px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <!-- BRAND -->

    <a
        href="{{ route('admin.dashboard') }}"
        class="brand"
    >
        Rakz Rental
    </a>


    <!-- DASHBOARD -->

    <a
        href="{{ route('admin.dashboard') }}"
        class="nav-link"
    >
        Dashboard
    </a>


    <!-- BOOKINGS -->

    <a
        href="{{ route('admin.bookings') }}"
        class="nav-link active"
    >
        Bookings
    </a>


    <!-- VEHICLES -->

    <a
        href="{{ route('admin.vehicles') }}"
        class="nav-link"
    >
        Vehicles
    </a>


    <!-- PROFILE -->

    <a
        href="{{ route('profile.show') }}"
        class="nav-link"
    >
        Profile
    </a>


</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <div class="container-fluid">


        <!-- PAGE TITLE -->

        <h1 class="page-title mb-2">
            Manage Bookings
        </h1>


        <p class="text-muted mb-4">
            Review and manage customer rental bookings.
        </p>


        <!-- SUCCESS MESSAGE -->

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        <!-- ERROR MESSAGE -->

        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        <!-- VALIDATION ERRORS -->

        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- BOOKINGS -->

        @if($bookings->count() > 0)


            @foreach($bookings as $booking)


                <div class="card booking-card shadow">


                    <div class="card-body p-4">


                        <!-- TOP -->

                        <div class="d-flex justify-content-between align-items-center mb-4">


                            <div>

                                <div class="vehicle-name">

                                    {{ $booking->vehicle->name }}

                                </div>


                                <small class="text-muted">

                                    {{ $booking->vehicle->registration }}

                                    -

                                    {{ $booking->vehicle->category }}

                                </small>

                            </div>


                            <!-- STATUS -->

                            @if($booking->status === 'confirmed')

                                <span class="badge status-confirmed">

                                    Confirmed

                                </span>

                            @else

                                <span class="badge status-cancelled">

                                    Cancelled

                                </span>

                            @endif


                        </div>


                        <!-- BOOKING INFORMATION -->

                        <div class="row">


                            <!-- CUSTOMER -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    CUSTOMER

                                </div>


                                <div class="booking-value">

                                    {{ $booking->customer_name }}

                                </div>

                            </div>


                            <!-- PICKUP -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    PICKUP DATE

                                </div>


                                <div class="booking-value">

                                    {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}

                                </div>

                            </div>


                            <!-- RETURN -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    RETURN DATE

                                </div>


                                <div class="booking-value">

                                    {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}

                                </div>

                            </div>


                            <!-- TOTAL -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    TOTAL

                                </div>


                                <div class="booking-value fw-bold">

                                    ${{ number_format($booking->total_amount, 2) }}

                                </div>

                            </div>


                        </div>


                        <!-- REPLACEMENT VEHICLE INFORMATION -->

                        @if($booking->replacement_vehicle_id && $booking->replacementVehicle)

                            <div class="replacement-info">

                                <div class="replacement-label">

                                    REPLACEMENT VEHICLE

                                </div>


                                <div class="replacement-value">

                                    {{ $booking->replacementVehicle->registration }}

                                    -

                                    {{ $booking->replacementVehicle->name }}

                                </div>


                                <small class="text-muted">

                                    {{ $booking->replacementVehicle->category }}

                                    ·

                                    ${{ number_format($booking->replacementVehicle->daily_rate, 2) }}/day

                                </small>

                            </div>

                        @endif


                        <!-- ACTIONS -->

                        @if($booking->status === 'confirmed')

                            <hr>


                            <div class="d-flex gap-2 flex-wrap">


                                <!-- REPLACE VEHICLE -->

                                <a
                                    href="{{ route('admin.bookings.replacement', $booking) }}"
                                    class="btn btn-replace"
                                >
                                    Replace Vehicle
                                </a>


                                <!-- CANCEL BOOKING -->

                                <form
                                    method="POST"
                                    action="{{ route('admin.bookings.status', $booking) }}"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');"
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
                                        class="btn btn-danger"
                                    >
                                        Cancel Booking
                                    </button>

                                </form>


                            </div>

                        @endif


                    </div>


                </div>


            @endforeach


        @else


            <!-- NO BOOKINGS -->

            <div class="card shadow border-0">


                <div class="card-body text-center p-5">


                    <h3 style="color: #1B3C53;">

                        No Bookings

                    </h3>


                    <p class="text-muted">

                        There are currently no customer bookings.

                    </p>


                </div>


            </div>


        @endif


    </div>


</div>


</body>

</html>