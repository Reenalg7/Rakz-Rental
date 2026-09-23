<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Booking History</title>

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
        }


        .nav-link:hover {
            background-color: #234C6A;
            color: white;
        }


        .nav-link.active {
            background-color: #456882;
            color: white;
        }


        .sidebar-bottom {
            margin-top: auto;
        }


        .logout-btn {
            width: 100%;
            text-align: left;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: white;
            font-size: 16px;
        }


        .logout-btn:hover {
            background-color: #234C6A;
        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .main-content {
            margin-left: 230px;
            padding: 40px;
        }


        /* =========================
           NOTIFICATIONS
        ========================= */

        .notification-card {
            border: none;
            border-left: 5px solid #456882;
            border-radius: 12px;
            background-color: white;
            margin-bottom: 15px;
        }


        .notification-title {
            color: #1B3C53;
            font-weight: bold;
            font-size: 17px;
        }


        .notification-message {
            color: #456882;
            margin-bottom: 8px;
        }


        .notification-date {
            color: #777;
            font-size: 13px;
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
            font-weight: bold;
            font-size: 20px;
        }


        .booking-label {
            color: #456882;
            font-weight: bold;
            font-size: 14px;
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
           REPLACEMENT INFORMATION
        ========================= */

        .replacement-info {
            background-color: #f1f5f7;
            border-left: 5px solid #456882;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            margin-bottom: 15px;
        }


        .replacement-title {
            color: #1B3C53;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 8px;
        }


        .replacement-original {
            color: #456882;
            font-size: 14px;
            margin-bottom: 4px;
        }


        .replacement-new {
            color: #1B3C53;
            font-size: 15px;
            font-weight: bold;
        }


        .replacement-note {
            color: #777;
            font-size: 13px;
            margin-top: 8px;
        }


        /* =========================
           CANCEL BUTTON
        ========================= */

        .cancel-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 14px;
        }


        .cancel-btn:hover {
            background-color: #bb2d3b;
            color: white;
        }


        /* =========================
           EMPTY CARD
        ========================= */

        .empty-card {
            border: none;
            border-radius: 15px;
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
        href="{{ route('vehicles.index') }}"
        class="brand"
    >
        Rakz Rental
    </a>


    <!-- VEHICLES -->

    <a
        href="{{ route('vehicles.index') }}"
        class="nav-link"
    >
        Vehicles
    </a>


    <!-- BOOKING HISTORY -->

    <a
        href="{{ route('bookings.index') }}"
        class="nav-link active"
    >
        Booking History
    </a>


    <!-- PROFILE -->

    <a
        href="{{ route('profile.show') }}"
        class="nav-link"
    >
        Profile
    </a>


    <!-- LOGOUT -->

    <div class="sidebar-bottom">

        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="logout-btn"
            >
                Logout
            </button>

        </form>

    </div>


</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <div class="container-fluid">


        <!-- PAGE TITLE -->

        <h1
            class="fw-bold mb-2"
            style="color: #1B3C53;"
        >
            My Booking History
        </h1>


        <p class="text-muted mb-4">
            View and track your vehicle rental bookings.
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


        <!-- =========================
             NOTIFICATIONS
        ========================= -->

        @if(isset($notifications) && $notifications->count() > 0)

            <div class="mb-4">

                <h3
                    class="fw-bold mb-3"
                    style="color: #1B3C53;"
                >
                    Notifications
                </h3>


                @foreach($notifications as $notification)

                    <div class="card notification-card shadow-sm">

                        <div class="card-body">

                            <div class="notification-title">
                                {{ $notification->title }}
                            </div>


                            <div class="notification-message mt-2">
                                {{ $notification->message }}
                            </div>


                            <div class="notification-date">
                                {{ $notification->created_at->format('d M Y, h:i A') }}
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif


        <!-- =========================
             BOOKINGS
        ========================= -->

        @if($bookings->count() > 0)


            @foreach($bookings as $booking)


                <div class="card booking-card shadow">


                    <div class="card-body p-4">


                        <!-- TOP SECTION -->

                        <div
                            class="d-flex justify-content-between align-items-center mb-4"
                        >

                            <div>

                                <div class="vehicle-name">

                                    @if($booking->replacementVehicle)

                                        {{ $booking->replacementVehicle->name }}

                                    @else

                                        {{ $booking->vehicle->name }}

                                    @endif

                                </div>


                                <small class="text-muted">

                                    @if($booking->replacementVehicle)

                                        {{ $booking->replacementVehicle->registration }}
                                        -
                                        {{ $booking->replacementVehicle->category }}

                                    @else

                                        {{ $booking->vehicle->registration }}
                                        -
                                        {{ $booking->vehicle->category }}

                                    @endif

                                </small>

                            </div>


                            <!-- BOOKING STATUS -->

                            @if($booking->status === 'confirmed')

                                <span class="badge status-confirmed">

                                    Confirmed

                                </span>

                            @elseif($booking->status === 'cancelled')

                                <span class="badge status-cancelled">

                                    Cancelled

                                </span>

                            @endif


                        </div>


                        <!-- =========================
                             REPLACEMENT NOTICE
                        ========================= -->

                        @if($booking->replacementVehicle)

                            <div class="replacement-info">

                                <div class="replacement-title">

                                    Vehicle Replaced

                                </div>


                                <div class="replacement-original">

                                    Original Vehicle:

                                    {{ $booking->vehicle->registration }}

                                    -

                                    {{ $booking->vehicle->name }}

                                </div>


                                <div class="replacement-new">

                                    New Vehicle:

                                    {{ $booking->replacementVehicle->registration }}

                                    -

                                    {{ $booking->replacementVehicle->name }}

                                </div>


                                <div class="replacement-note">

                                    Your booking dates and total amount remain unchanged.

                                </div>

                            </div>

                        @endif


                        <!-- =========================
                             BOOKING INFORMATION
                        ========================= -->

                        <div class="row">


                            <!-- PICKUP -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    Pickup Date

                                </div>


                                <div class="booking-value">

                                    {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}

                                </div>

                            </div>


                            <!-- RETURN -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    Return Date

                                </div>


                                <div class="booking-value">

                                    {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}

                                </div>

                            </div>


                            <!-- DAYS -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    Rental Days

                                </div>


                                <div class="booking-value">

                                    {{ \Carbon\Carbon::parse($booking->pickup_date)->diffInDays(\Carbon\Carbon::parse($booking->return_date)) }}

                                    day(s)

                                </div>

                            </div>


                            <!-- TOTAL -->

                            <div class="col-md-3 mb-3">

                                <div class="booking-label">

                                    Total Amount

                                </div>


                                <div class="booking-value fw-bold">

                                    ${{ number_format($booking->total_amount, 2) }}

                                </div>

                            </div>


                        </div>


                        <hr>


                        <!-- =========================
                             BOOKING FOOTER
                        ========================= -->

                        <div
                            class="d-flex justify-content-between align-items-center"
                        >


                            <small class="text-muted">

                                Booking created:

                                {{ $booking->created_at->format('d M Y') }}

                            </small>


                            <!-- CANCEL BOOKING -->

                            @if($booking->status === 'confirmed')

                                <form
                                    action="{{ route('bookings.cancel', $booking) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                                >

                                    @csrf

                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        class="cancel-btn"
                                    >
                                        Cancel Booking
                                    </button>

                                </form>

                            @endif


                        </div>


                    </div>


                </div>


            @endforeach


        @else


            <!-- =========================
                 NO BOOKINGS
            ========================= -->

            <div class="card empty-card shadow">

                <div class="card-body text-center p-5">


                    <div
                        style="font-size: 50px;"
                    >
                        🚗
                    </div>


                    <h3
                        class="mt-3"
                        style="color: #1B3C53;"
                    >
                        No Bookings Yet
                    </h3>


                    <p class="text-muted">

                        You haven't made any vehicle bookings yet.

                    </p>


                    <a
                        href="{{ route('vehicles.index') }}"
                        class="btn"
                        style="background-color: #234C6A; color: white;"
                    >
                        Browse Vehicles
                    </a>


                </div>

            </div>


        @endif


    </div>

</div>


</body>

</html>