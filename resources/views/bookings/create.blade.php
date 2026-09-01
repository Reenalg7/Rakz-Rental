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

        .booking-card {
            border: none;
            border-radius: 12px;
        }

        .page-title {
            color: #1B3C53;
        }

        .vehicle-name {
            color: #234C6A;
        }

        .price {
            color: #234C6A;
        }

        .check-btn {
            background-color: #234C6A;
            color: white;
            border: none;
        }

        .check-btn:hover {
            background-color: #1B3C53;
            color: white;
        }

        .back-link {
            color: #456882;
            text-decoration: none;
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


    <!-- Booking Form -->

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="card booking-card shadow">

                    <div class="card-body p-4">

                        <h2 class="page-title text-center fw-bold mb-4">
                            Book Vehicle
                        </h2>


                        <h3 class="vehicle-name fw-bold">
                            {{ $vehicle->name }}
                        </h3>

                        <p class="text-muted">
                            Category: {{ $vehicle->category }}
                        </p>

                        <h4 class="price fw-bold mb-4">

                            ${{ number_format($vehicle->daily_rate, 2) }}

                            <small class="text-muted">
                                / day
                            </small>

                        </h4>


                        <!-- Error -->

                        @if(session('error'))

                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>

                        @endif


                        <!-- Validation Errors -->

                        @if($errors->any())

                            <div class="alert alert-danger">

                                @foreach($errors->all() as $error)

                                    <div>{{ $error }}</div>

                                @endforeach

                            </div>

                        @endif


                        <!-- Form -->

                        <form
                            method="POST"
                            action="{{ route('bookings.store', $vehicle) }}"
                        >

                            @csrf
                           <div class="mb-3">

                             <label class="form-label fw-semibold">
                               Customer Name
                              </label>

                             <input
                              type="text"
                             name="customer_name"
                               value="{{ old('customer_name') }}"
                              class="form-control"
                              placeholder="Enter your name"
                              required
                              >

                        </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Pickup Date
                                </label>

                                <input
                                    type="date"
                                    name="pickup_date"
                                    value="{{ old('pickup_date') }}"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Return Date
                                </label>

                                <input
                                    type="date"
                                    name="return_date"
                                    value="{{ old('return_date') }}"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <button
                                type="submit"
                                class="btn check-btn w-100"
                            >

                                Check Availability

                            </button>

                        </form>


                        <div class="text-center mt-3">

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

</body>

</html>