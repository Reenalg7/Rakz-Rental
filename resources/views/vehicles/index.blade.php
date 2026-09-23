<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Vehicles</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        /* =========================
           GENERAL
        ========================= */

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

            font-size: 16px;

            transition: 0.2s;
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
        }


        /* =========================
           MODERN SEARCH AREA
        ========================= */

        .search-card {
            background-color: white;

            border: none;

            border-radius: 18px;

            padding: 24px;

            margin-bottom: 30px;

            box-shadow: 0 8px 25px rgba(27, 60, 83, 0.08);
        }


        .search-label {
            color: #1B3C53;

            font-weight: 600;

            font-size: 14px;

            margin-bottom: 8px;

            display: block;
        }


        .search-wrapper {
            position: relative;
        }


        .search-icon {
            position: absolute;

            left: 16px;

            top: 50%;

            transform: translateY(-50%);

            color: #456882;

            font-size: 17px;

            z-index: 2;
        }


        .search-input {
            height: 50px;

            border: 1px solid #D2C1B6;

            border-radius: 12px;

            padding: 10px 45px 10px 45px;

            font-size: 15px;

            transition: 0.2s;
        }


        .search-input:focus {
            border-color: #456882;

            box-shadow: 0 0 0 4px rgba(69, 104, 130, 0.12);

            outline: none;
        }


        .search-input::placeholder {
            color: #8a8a8a;
        }


        .search-btn {
            height: 50px;

            background-color: #234C6A;

            color: white;

            border: none;

            border-radius: 12px;

            font-weight: 600;

            transition: 0.2s;
        }


        .search-btn:hover {
            background-color: #1B3C53;

            color: white;

            transform: translateY(-1px);
        }


        .clear-search {
            color: #456882;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }


        .clear-search:hover {
            color: #1B3C53;

            text-decoration: underline;
        }


        /* =========================
           VEHICLE CARDS
        ========================= */

        .vehicle-card {
            border: none;

            border-radius: 14px;

            transition: 0.2s;

            overflow: hidden;
        }


        .vehicle-card:hover {
            transform: translateY(-4px);

            box-shadow: 0 10px 25px rgba(27, 60, 83, 0.12) !important;
        }


        .vehicle-category {
            color: #456882;
        }


        .registration {
            color: #6c757d;

            font-size: 14px;
        }


        .price {
            color: #234C6A;
        }


        .book-btn {
            background-color: #234C6A;

            color: white;

            border: none;

            border-radius: 8px;

            padding: 10px;
        }


        .book-btn:hover {
            background-color: #1B3C53;

            color: white;
        }


        .unavailable-btn {
            background-color: #adb5bd;

            color: white;

            border: none;

            border-radius: 8px;

            cursor: not-allowed;

            padding: 10px;
        }


        /* =========================
           STATUS
        ========================= */

        .available-badge {
            background-color: #d1e7dd;

            color: #0f5132;

            padding: 7px 10px;
        }


        .unavailable-badge {
            background-color: #f8d7da;

            color: #842029;

            padding: 7px 10px;
        }


        /* =========================
           NO RESULTS
        ========================= */

        .no-results {
            background-color: white;

            border-radius: 14px;

            padding: 50px 30px;

            text-align: center;

            color: #456882;

            box-shadow: 0 8px 25px rgba(27, 60, 83, 0.08);
        }


        .no-results h4 {
            color: #1B3C53;

            font-weight: bold;
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


        @media (max-width: 576px) {

            .sidebar {
                position: relative;

                width: 100%;

                height: auto;
            }


            .main-content {
                margin-left: 0;

                padding: 20px;
            }


            .brand {
                margin-bottom: 20px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     LEFT SIDEBAR
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
        class="nav-link active"
    >
        Vehicles
    </a>


    <!-- BOOKING HISTORY -->

    <a
        href="{{ route('bookings.index') }}"
        class="nav-link"
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

</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">

    <div class="container-fluid">


        <!-- =========================
             PAGE HEADER
        ========================= -->

        <div class="mb-4">

            <h1 class="page-title fw-bold">
                Available Vehicles
            </h1>

            <p class="text-muted">
                Browse our vehicles and choose the one that suits your rental needs.
            </p>

        </div>


        <!-- =========================
             MODERN SEARCH
        ========================= -->

        <div class="search-card">

            <form
                method="GET"
                action="{{ route('vehicles.index') }}"
            >

                <div class="row g-3 align-items-end">

                    <!-- SEARCH FIELD -->

                    <div class="col-lg-10">

                        <label class="search-label">
                            Search Vehicles
                        </label>

                        <div class="search-wrapper">

                            <span class="search-icon">
                                🔍
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control search-input"
                                placeholder="Search by vehicle name, registration or category..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>


                    <!-- SEARCH BUTTON -->

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn search-btn w-100"
                        >
                            Search
                        </button>

                    </div>

                </div>

            </form>


            <!-- CLEAR SEARCH -->

            @if(request('search'))

                <div class="mt-3">

                    <a
                        href="{{ route('vehicles.index') }}"
                        class="clear-search"
                    >
                        ✕ Clear Search
                    </a>

                </div>

            @endif

        </div>


        <!-- =========================
             VEHICLES
        ========================= -->

        <div class="row">


            @forelse ($vehicles as $vehicle)


                <div class="col-lg-4 col-md-6 mb-4">


                    <div class="card vehicle-card shadow-sm h-100">


                        <div class="card-body p-4">


                            <!-- VEHICLE NAME -->

                            <h3 class="card-title fw-bold">

                                {{ $vehicle->name }}

                            </h3>


                            <!-- CATEGORY -->

                            <p class="vehicle-category fw-semibold mb-1">

                                {{ $vehicle->category }}

                            </p>


                            <!-- REGISTRATION -->

                            <p class="registration mb-3">

                                Registration:

                                {{ $vehicle->registration }}

                            </p>


                            <!-- PRICE -->

                            <h4 class="price fw-bold">

                                ${{ number_format($vehicle->daily_rate, 2) }}

                                <small class="text-muted">
                                    / day
                                </small>

                            </h4>


                            <!-- STATUS -->

                            @if($vehicle->status === 'available')


                                <span class="badge available-badge mb-3">

                                    Available

                                </span>


                                <br>


                                <!-- BOOK -->

                                <a
                                    href="{{ route('bookings.create', $vehicle) }}"
                                    class="btn book-btn w-100"
                                >
                                    Book Now
                                </a>


                            @else


                                <span class="badge unavailable-badge mb-3">

                                    Unavailable

                                </span>


                                <br>


                                <!-- UNAVAILABLE -->

                                <button
                                    class="btn unavailable-btn w-100"
                                    disabled
                                >
                                    Currently Unavailable
                                </button>


                            @endif


                        </div>


                    </div>


                </div>


            @empty


                <!-- =========================
                     NO RESULTS
                ========================= -->

                <div class="col-12">

                    <div class="no-results">

                        <h4>
                            No vehicles found
                        </h4>

                        <p class="mb-0">
                            Try changing your search.
                        </p>

                    </div>

                </div>


            @endforelse


        </div>


    </div>

</div>


</body>

</html>