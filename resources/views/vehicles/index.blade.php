<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rakz Rental - Vehicles</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background-color: #D2C1B6;
        }

        /* Sidebar */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 230px;
            height: 100vh;
            background-color: #1B3C53;
            padding: 25px 15px;
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

        /* Main Content */

        .main-content {
            margin-left: 230px;
            padding: 40px;
        }

        .page-title {
            color: #1B3C53;
        }

        /* Vehicle Cards */

        .vehicle-card {
            border: none;
            border-radius: 12px;
        }

        .vehicle-card:hover {
            transform: translateY(-3px);
            transition: 0.2s;
        }

        .vehicle-category {
            color: #456882;
        }

        .price {
            color: #234C6A;
        }

        .book-btn {
            background-color: #234C6A;
            color: white;
            border: none;
        }

        .book-btn:hover {
            background-color: #1B3C53;
            color: white;
        }

    </style>

</head>


<body>


    <!-- LEFT SIDEBAR -->

    <div class="sidebar">

        <a
            href="{{ route('vehicles.index') }}"
            class="brand"
        >
            Rakz Rental
        </a>


        <a
            href="{{ route('vehicles.index') }}"
            class="nav-link active"
        >
            🚗 Vehicles
        </a>


        <a
            href="{{ route('bookings.index') }}"
            class="nav-link"
        >
            📋 Booking History
        </a>


        <a
            href="{{ route('admin.bookings') }}"
            class="nav-link"
        >
            ⚙️ Admin
        </a>

    </div>


    <!-- MAIN CONTENT -->

    <div class="main-content">

        <div class="container-fluid">


            <div class="mb-5">

                <h1 class="page-title fw-bold">
                    Available Vehicles
                </h1>

                <p class="text-muted">
                    Choose a vehicle for your rental
                </p>

            </div>


            <div class="row">

                @foreach ($vehicles as $vehicle)

                    <div class="col-md-4 mb-4">

                        <div class="card vehicle-card shadow-sm h-100">

                            <div class="card-body p-4">

                                <h3 class="card-title fw-bold">
                                    {{ $vehicle->name }}
                                </h3>

                                <p class="vehicle-category fw-semibold">
                                    {{ $vehicle->category }}
                                </p>

                                <h4 class="price fw-bold">

                                    ${{ number_format($vehicle->daily_rate, 2) }}

                                    <small class="text-muted">
                                        / day
                                    </small>

                                </h4>

                                <span
                                    class="badge mb-3"
                                    style="background-color: #456882;"
                                >
                                    {{ ucfirst($vehicle->status) }}
                                </span>

                                <br>

                                <a
                                    href="{{ route('bookings.create', $vehicle) }}"
                                    class="btn book-btn w-100"
                                >
                                    Book Now
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


</body>

</html>