<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Replace Vehicle - Rakz Rental</title>

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


        /* SIDEBAR */

        .sidebar {
            position: fixed;

            top: 0;
            left: 0;

            width: 230px;
            height: 100vh;

            background-color: #1B3C53;

            padding-top: 25px;

            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            color: white;

            text-align: center;

            margin-bottom: 30px;

            font-weight: bold;
        }

        .sidebar a {
            color: white;

            text-decoration: none;

            padding: 14px 25px;

            margin: 4px 10px;

            border-radius: 8px;
        }

        .sidebar a:hover {
            background-color: #234C6A;
        }

        .sidebar a.active {
            background-color: #456882;
        }


        /* MAIN */

        .main-content {
            margin-left: 230px;

            padding: 40px;

            min-height: 100vh;
        }


        /* CARD */

        .replacement-card {
            background-color: white;

            border-radius: 18px;

            border: none;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }


        .page-title {
            color: #1B3C53;

            font-weight: bold;
        }


        .subtitle {
            color: #6c757d;
        }


        /* OLD VEHICLE */

        .old-vehicle {
            background-color: #f7f4f2;

            border-radius: 12px;

            padding: 20px;

            margin-bottom: 25px;
        }

        .old-title {
            color: #456882;

            font-size: 13px;

            font-weight: bold;

            text-transform: uppercase;

            margin-bottom: 5px;
        }

        .old-name {
            color: #1B3C53;

            font-size: 22px;

            font-weight: bold;
        }


        /* FORM */

        .form-label {
            color: #1B3C53;

            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 9px;

            padding: 11px;

            border: 1px solid #d5d5d5;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #456882;

            box-shadow: 0 0 0 0.2rem rgba(69, 104, 130, 0.15);
        }


        /* INFORMATION */

        .info-box {
            background-color: #f7f4f2;

            border-radius: 10px;

            padding: 15px;

            margin-bottom: 25px;

            color: #6c757d;

            font-size: 14px;
        }


        /* BUTTON */

        .replace-btn {
            background-color: #234C6A;

            color: white;

            border: none;

            border-radius: 9px;

            padding: 12px 20px;

            font-weight: 600;
        }

        .replace-btn:hover {
            background-color: #1B3C53;

            color: white;
        }


        .cancel-btn {
            color: #456882;

            border-color: #456882;

            border-radius: 9px;

            padding: 11px 20px;

            font-weight: 600;
        }

        .cancel-btn:hover {
            background-color: #456882;

            color: white;
        }


        /* ERRORS */

        .alert {
            border-radius: 10px;
        }


        /* RESPONSIVE */

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


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>
        Rakz Rental
    </h2>

    <a href="{{ route('admin.dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('admin.bookings') }}">
        Bookings
    </a>

    <a
        href="{{ route('admin.vehicles') }}"
        class="active"
    >
        Vehicles
    </a>

    <a href="{{ route('profile.show') }}">
        Profile
    </a>

</div>


<!-- MAIN -->

<div class="main-content">

    <div class="container-fluid">

        <div class="row justify-content-center">

            <div class="col-lg-8">


                <div class="card replacement-card">

                    <div class="card-body p-4 p-md-5">


                        <!-- TITLE -->

                        <div class="text-center mb-4">

                            <h1 class="page-title">
                                Replace Vehicle
                            </h1>

                            <p class="subtitle">
                                Add a replacement vehicle to the fleet.
                            </p>

                        </div>


                        <!-- OLD VEHICLE -->

                        <div class="old-vehicle">

                            <div class="old-title">
                                Vehicle Being Replaced
                            </div>

                            <div class="old-name">
                                {{ $vehicle->name }}
                            </div>

                            <div class="text-muted mt-1">

                                Registration:
                                <strong>
                                    {{ $vehicle->registration }}
                                </strong>

                            </div>

                            <div class="text-muted">

                                Category:
                                <strong>
                                    {{ $vehicle->category }}
                                </strong>

                            </div>

                        </div>


                        <!-- INFORMATION -->

                        <div class="info-box">

                            <strong>
                                Important:
                            </strong>

                            The current vehicle will be marked as
                            unavailable. Its existing booking history
                            will be preserved. The replacement vehicle
                            will be added to the fleet as available.

                        </div>


                        <!-- VALIDATION ERRORS -->

                        @if($errors->any())

                            <div class="alert alert-danger">

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


                        <!-- FORM -->

                        <form
                            method="POST"
                            action="{{ route('admin.vehicles.replace', $vehicle) }}"
                        >

                            @csrf


                            <!-- REGISTRATION -->

                            <div class="mb-3">

                                <label
                                    for="registration"
                                    class="form-label"
                                >
                                    Replacement Registration
                                </label>

                                <input
                                    type="text"
                                    id="registration"
                                    name="registration"
                                    class="form-control"
                                    value="{{ old('registration') }}"
                                    placeholder="Example: LR6000"
                                    required
                                >

                            </div>


                            <!-- VEHICLE NAME -->

                            <div class="mb-3">

                                <label
                                    for="name"
                                    class="form-label"
                                >
                                    Vehicle Name
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="Example: Toyota Corolla"
                                    required
                                >

                            </div>


                            <!-- CATEGORY -->

                            <div class="mb-3">

                                <label
                                    for="category"
                                    class="form-label"
                                >
                                    Category
                                </label>

                                <select
                                    id="category"
                                    name="category"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Category
                                    </option>

                                    <option
                                        value="Sedan"
                                        {{ old('category') == 'Sedan' ? 'selected' : '' }}
                                    >
                                        Sedan
                                    </option>

                                    <option
                                        value="SUV"
                                        {{ old('category') == 'SUV' ? 'selected' : '' }}
                                    >
                                        SUV
                                    </option>

                                    <option
                                        value="Van"
                                        {{ old('category') == 'Van' ? 'selected' : '' }}
                                    >
                                        Van
                                    </option>

                                    <option
                                        value="MPV"
                                        {{ old('category') == 'MPV' ? 'selected' : '' }}
                                    >
                                        MPV
                                    </option>

                                    <option
                                        value="Pickup"
                                        {{ old('category') == 'Pickup' ? 'selected' : '' }}
                                    >
                                        Pickup
                                    </option>

                                    <option
                                        value="Hatchback"
                                        {{ old('category') == 'Hatchback' ? 'selected' : '' }}
                                    >
                                        Hatchback
                                    </option>

                                    <option
                                        value="Wagon"
                                        {{ old('category') == 'Wagon' ? 'selected' : '' }}
                                    >
                                        Wagon
                                    </option>

                                </select>

                            </div>


                            <!-- DAILY RATE -->

                            <div class="mb-4">

                                <label
                                    for="daily_rate"
                                    class="form-label"
                                >
                                    Daily Rate
                                </label>

                                <input
                                    type="number"
                                    id="daily_rate"
                                    name="daily_rate"
                                    class="form-control"
                                    value="{{ old('daily_rate') }}"
                                    placeholder="Example: 150"
                                    min="0"
                                    step="0.01"
                                    required
                                >

                            </div>


                            <!-- BUTTONS -->

                            <div class="d-flex gap-2">

                                <a
                                    href="{{ route('admin.vehicles') }}"
                                    class="btn btn-outline-secondary cancel-btn"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="btn replace-btn"
                                >
                                    Replace Vehicle
                                </button>

                            </div>

                        </form>


                    </div>

                </div>


            </div>

        </div>

    </div>

</div>


</body>

</html>