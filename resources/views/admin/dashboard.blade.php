<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Admin Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .page-subtitle {
            color: #6c757d;
        }


        /* =========================
           STAT CARDS
        ========================= */

        .stat-card {
            border: none;

            border-radius: 16px;

            height: 100%;

            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-title {
            color: #456882;

            font-size: 13px;

            font-weight: bold;

            letter-spacing: 0.5px;
        }

        .stat-number {
            color: #1B3C53;

            font-size: 32px;

            font-weight: bold;

            margin-top: 8px;
        }

        .stat-description {
            color: #888;

            font-size: 13px;

            margin-top: 5px;
        }


        /* =========================
           ANALYTICS CARDS
        ========================= */

        .analytics-card {
            border: none;

            border-radius: 16px;

            height: 100%;
        }

        .analytics-title {
            color: #1B3C53;

            font-weight: bold;
        }

        .analytics-subtitle {
            color: #888;

            font-size: 13px;
        }

        .chart-container {
            position: relative;

            height: 280px;
        }


        /* =========================
           BOOKING STATUS SUMMARY
        ========================= */

        .summary-box {
            background-color: #f7f4f2;

            border-radius: 12px;

            padding: 18px;

            margin-bottom: 12px;
        }

        .summary-label {
            color: #6c757d;

            font-size: 13px;
        }

        .summary-number {
            color: #1B3C53;

            font-size: 25px;

            font-weight: bold;
        }


        /* =========================
           RECENT BOOKINGS
        ========================= */

        .booking-card {
            border: none;

            border-radius: 16px;
        }

        .booking-table th {
            color: #456882;

            font-size: 13px;
        }

        .booking-table td {
            color: #333;

            font-size: 14px;
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
           VIEW ALL BUTTON
        ========================= */

        .view-all-btn {
            background-color: #234C6A;

            color: white;

            border: none;

            border-radius: 8px;

            padding: 9px 16px;

            font-weight: 600;
        }

        .view-all-btn:hover {
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


<!-- ========================================
     SIDEBAR
======================================== -->

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
        class="nav-link active"
    >
        Dashboard
    </a>


    <!-- BOOKINGS -->

    <a
        href="{{ route('admin.bookings') }}"
        class="nav-link"
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


<!-- ========================================
     MAIN CONTENT
======================================== -->

<div class="main-content">

    <div class="container-fluid">


        <!-- PAGE TITLE -->

        <h1 class="page-title mb-2">
            Admin Dashboard
        </h1>

        <p class="page-subtitle mb-4">
            Welcome back, {{ auth()->user()->name }}.
            Here's an overview of Rakz Rental.
        </p>


        <!-- ========================================
             BOOKING STATISTICS
        ======================================== -->

        <div class="row g-4 mb-4">


            <!-- TOTAL -->

            <div class="col-xl-4 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="stat-title">
                            TOTAL BOOKINGS
                        </div>

                        <div class="stat-number">
                            {{ $totalBookings }}
                        </div>

                        <div class="stat-description">
                            All rental bookings
                        </div>

                    </div>

                </div>

            </div>


            <!-- CONFIRMED -->

            <div class="col-xl-4 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="stat-title">
                            CONFIRMED
                        </div>

                        <div class="stat-number">
                            {{ $confirmedBookings }}
                        </div>

                        <div class="stat-description">
                            Confirmed rentals
                        </div>

                    </div>

                </div>

            </div>


            <!-- CANCELLED -->

            <div class="col-xl-4 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="stat-title">
                            CANCELLED
                        </div>

                        <div class="stat-number">
                            {{ $cancelledBookings }}
                        </div>

                        <div class="stat-description">
                            Cancelled rentals
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================
             VEHICLE STATISTICS
        ======================================== -->

        <div class="row g-4 mb-4">


            <!-- TOTAL VEHICLES -->

            <div class="col-md-4">

                <div class="card stat-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="stat-title">
                            TOTAL VEHICLES
                        </div>

                        <div class="stat-number">
                            {{ $totalVehicles }}
                        </div>

                        <div class="stat-description">
                            Vehicles in the system
                        </div>

                    </div>

                </div>

            </div>


            <!-- AVAILABLE -->

            <div class="col-md-4">

                <div class="card stat-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="stat-title">
                            AVAILABLE VEHICLES
                        </div>

                        <div class="stat-number">
                            {{ $availableVehicles }}
                        </div>

                        <div class="stat-description">
                            Ready for rental
                        </div>

                    </div>

                </div>

            </div>


            <!-- UNAVAILABLE -->

            <div class="col-md-4">

                <div class="card stat-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="stat-title">
                            UNAVAILABLE VEHICLES
                        </div>

                        <div class="stat-number">
                            {{ $unavailableVehicles }}
                        </div>

                        <div class="stat-description">
                            Maintenance or unavailable
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================
             ANALYTICS
        ======================================== -->

        <div class="row g-4 mb-4">


            <!-- BOOKING CHART -->

            <div class="col-lg-8">

                <div class="card analytics-card shadow-sm">

                    <div class="card-body p-4">

                        <h4 class="analytics-title mb-1">
                            Booking Analytics
                        </h4>

                        <p class="analytics-subtitle mb-4">
                            Overview of booking statuses
                        </p>

                        <div class="chart-container">

                            <canvas id="bookingChart"></canvas>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SUMMARY -->

            <div class="col-lg-4">

                <div class="card analytics-card shadow-sm">

                    <div class="card-body p-4">

                        <h4 class="analytics-title mb-1">
                            Booking Summary
                        </h4>

                        <p class="analytics-subtitle mb-4">
                            Current booking status
                        </p>


                        <!-- CONFIRMED -->

                        <div class="summary-box">

                            <div class="summary-label">
                                Confirmed Bookings
                            </div>

                            <div class="summary-number">
                                {{ $confirmedBookings }}
                            </div>

                        </div>


                        <!-- CANCELLED -->

                        <div class="summary-box">

                            <div class="summary-label">
                                Cancelled Bookings
                            </div>

                            <div class="summary-number">
                                {{ $cancelledBookings }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================
             RECENT BOOKINGS
        ======================================== -->

        <div class="card booking-card shadow-sm mb-4">

            <div class="card-body p-4">


                <!-- HEADER -->

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4
                            class="fw-bold mb-1"
                            style="color: #1B3C53;"
                        >
                            Recent Bookings
                        </h4>

                        <p class="text-muted mb-0">
                            Latest vehicle rental bookings.
                        </p>

                    </div>


                    <!-- VIEW ALL -->

                    <a
                        href="{{ route('admin.bookings') }}"
                        class="btn view-all-btn"
                    >
                        View All
                    </a>

                </div>


                <!-- TABLE -->

                @if($recentBookings->count() > 0)

                    <div class="table-responsive">

                        <table class="table booking-table align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Vehicle
                                    </th>

                                    <th>
                                        Pickup
                                    </th>

                                    <th>
                                        Return
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($recentBookings as $booking)

                                    <tr>

                                        <!-- CUSTOMER -->

                                        <td>
                                            {{ $booking->customer_name }}
                                        </td>


                                        <!-- VEHICLE -->

                                        <td>
                                            {{ $booking->vehicle->name }}
                                        </td>


                                        <!-- PICKUP -->

                                        <td>
                                            {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}
                                        </td>


                                        <!-- RETURN -->

                                        <td>
                                            {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}
                                        </td>


                                        <!-- TOTAL -->

                                        <td>
                                            ${{ number_format($booking->total_amount, 2) }}
                                        </td>


                                        <!-- STATUS -->

                                        <td>

                                            @if($booking->status === 'confirmed')

                                                <span class="badge status-confirmed">
                                                    Confirmed
                                                </span>

                                            @else

                                                <span class="badge status-cancelled">
                                                    Cancelled
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <h5 style="color: #1B3C53;">
                            No bookings yet
                        </h5>

                        <p class="text-muted">
                            New bookings will appear here.
                        </p>

                    </div>

                @endif

            </div>

        </div>


    </div>

</div>


<!-- ========================================
     BOOKING CHART
======================================== -->

<script>

    const bookingChartData = @json($bookingChart);

    const ctx = document
        .getElementById('bookingChart')
        .getContext('2d');

    new Chart(ctx, {

        type: 'doughnut',

        data: {

            labels: [
                'Confirmed',
                'Cancelled'
            ],

            datasets: [{

                data: [
                    bookingChartData.confirmed,
                    bookingChartData.cancelled
                ],

                borderWidth: 2

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'bottom'

                }

            }

        }

    });

</script>


</body>

</html>