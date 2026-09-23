<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rakz Rental - Book Vehicle</title>

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

        .booking-section {
            padding: 50px 0;
        }

        .booking-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background-color: white;
        }

        /* Vehicle Information */

        .vehicle-section {
            background-color: #f7f4f2;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
        }

        .vehicle-label {
            color: #456882;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .vehicle-name {
            color: #1B3C53;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .vehicle-category {
            color: #456882;
            margin-bottom: 18px;
        }

        .price {
            color: #234C6A;
            font-size: 24px;
            font-weight: 700;
        }

        .price small {
            color: #777;
            font-size: 14px;
            font-weight: 400;
        }

        /* Page Heading */

        .page-title {
            color: #1B3C53;
            font-weight: 700;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 15px;
        }

        /* Date Inputs */

        .form-label {
            color: #1B3C53;
            font-weight: 600;
        }

        .form-control {
            border: 1px solid #d8d8d8;
            border-radius: 10px;
            padding: 12px 14px;
            height: 50px;
        }

        .form-control:focus {
            border-color: #456882;
            box-shadow: 0 0 0 0.2rem rgba(69, 104, 130, 0.15);
        }

        /* Information Box */

        .booking-info {
            background-color: #f7f4f2;
            border-radius: 12px;
            padding: 15px 18px;
            margin-bottom: 25px;
        }

        .booking-info-title {
            color: #234C6A;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .booking-info-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 0;
        }

        /* Confirm Button */

        .confirm-btn {
            background-color: #234C6A;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 16px;
            font-weight: 600;
            transition: 0.2s;
        }

        .confirm-btn:hover {
            background-color: #1B3C53;
            color: white;
        }

        /* Back Link */

        .back-link {
            color: #456882;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #1B3C53;
        }

        /* Error Messages */

        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Responsive */

        @media (max-width: 768px) {

            .booking-section {
                padding: 25px 15px;
            }

            .vehicle-name {
                font-size: 24px;
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


    <!-- Booking Section -->

    <div class="container booking-section">

        <div class="row justify-content-center">

            <div class="col-lg-7 col-md-9">

                <div class="card booking-card shadow">

                    <div class="card-body p-4 p-md-5">

                        <!-- Heading -->

                        <div class="text-center mb-4">

                            <h2 class="page-title mb-2">
                                Book Your Vehicle
                            </h2>

                            <p class="page-subtitle">
                                Select your rental dates to continue
                            </p>

                        </div>


                        <!-- Vehicle Information -->

                        <div class="vehicle-section">

                            <div class="vehicle-label">
                                Selected Vehicle
                            </div>

                            <div class="vehicle-name">
                                {{ $vehicle->name }}
                            </div>

                            <div class="vehicle-category">
                                {{ $vehicle->category }}
                            </div>

                            <div class="price">

                                ${{ number_format($vehicle->daily_rate, 2) }}

                                <small>
                                    / day
                                </small>

                            </div>

                        </div>


                        <!-- Error Message -->

                        @if(session('error'))

                            <div class="alert alert-danger mb-4">

                                <strong>
                                    Booking Unavailable
                                </strong>

                                <div class="mt-1">
                                    {{ session('error') }}
                                </div>

                            </div>

                        @endif


                        <!-- Validation Errors -->

                        @if($errors->any())

                            <div class="alert alert-danger mb-4">

                                <strong>
                                    Please check the following:
                                </strong>

                                <ul class="mb-0 mt-2">

                                    @foreach($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <!-- Booking Information -->

                        <div class="booking-info">

                            <div class="booking-info-title">
                                Booking Information
                            </div>

                            <p class="booking-info-text">
                                Your selected dates will be checked before
                                the booking is confirmed. If the vehicle is
                                available, your booking will be confirmed
                                automatically.
                            </p>

                        </div>


                        <!-- Booking Form -->

                        <form
                            method="POST"
                            action="{{ route('bookings.store', $vehicle) }}"
                        >

                            @csrf


                            <!-- Pickup Date -->

                            <div class="mb-4">

                                <label
                                    for="pickup_date"
                                    class="form-label"
                                >
                                    Pickup Date
                                </label>

                                <input
                                    type="date"
                                    id="pickup_date"
                                    name="pickup_date"
                                    value="{{ old('pickup_date') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <!-- Return Date -->

                            <div class="mb-4">

                                <label
                                    for="return_date"
                                    class="form-label"
                                >
                                    Return Date
                                </label>

                                <input
                                    type="date"
                                    id="return_date"
                                    name="return_date"
                                    value="{{ old('return_date') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <!-- Submit -->

                            <button
                                type="submit"
                                class="btn confirm-btn w-100"
                            >
                                Confirm Booking
                            </button>

                        </form>


                        <!-- Back -->

                        <div class="text-center mt-4">

                            <a
                                href="{{ route('vehicles.index') }}"
                                class="back-link"
                            >
                                ← Back to Vehicles
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Date Validation -->

    <script>

        const pickupDate = document.getElementById('pickup_date');
        const returnDate = document.getElementById('return_date');

        pickupDate.addEventListener('change', function () {

            returnDate.min = this.value;

        });

    </script>

</body>

</html>