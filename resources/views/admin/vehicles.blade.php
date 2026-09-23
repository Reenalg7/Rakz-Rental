<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Vehicles - Rakz Rental</title>

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
            top: 0;
            left: 0;

            width: 230px;
            height: 100vh;

            background-color: #1B3C53;

            padding-top: 25px;

            color: white;

            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;

            margin-bottom: 30px;

            font-weight: bold;
        }

        .sidebar a {
            display: block;

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


        /* =========================
           MAIN CONTENT
        ========================= */

        .main-content {
            margin-left: 230px;

            padding: 40px;

            min-height: 100vh;
        }


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;
        }

        .page-header h1 {
            color: #1B3C53;

            font-weight: bold;

            margin: 0;
        }

        .page-header p {
            margin-top: 8px;

            color: #456882;
        }


        /* =========================
           ADD VEHICLE BUTTON
        ========================= */

        .add-btn {
            background-color: #1B3C53;

            color: white;

            border: none;

            padding: 12px 20px;

            border-radius: 8px;

            text-decoration: none;

            font-weight: bold;
        }

        .add-btn:hover {
            background-color: #234C6A;

            color: white;
        }


        /* =========================
           ALERTS
        ========================= */

        .alert {
            border-radius: 8px;
        }


        /* =========================
           TABLE CARD
        ========================= */

        .table-card {
            background-color: white;

            border-radius: 12px;

            padding: 25px;

            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-card h4 {
            color: #1B3C53;

            font-weight: bold;

            margin-bottom: 20px;
        }

        .table {
            margin-bottom: 0;

            vertical-align: middle;
        }

        .table thead {
            background-color: #1B3C53;

            color: white;
        }

        .table thead th {
            background-color: #1B3C53;

            color: white;

            padding: 15px;

            border: none;
        }

        .table tbody td {
            padding: 15px;
        }

        .table tbody tr:hover {
            background-color: #f5f5f5;
        }


        /* =========================
           STATUS
        ========================= */

        .status-available {
            background-color: #d1e7dd;

            color: #0f5132;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;
        }

        .status-unavailable {
            background-color: #f8d7da;

            color: #842029;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;
        }


        /* =========================
           ACTION BUTTONS
        ========================= */

        .action-btn {
            display: inline-block;

            border: none;

            padding: 7px 12px;

            border-radius: 6px;

            font-size: 13px;

            text-decoration: none;

            margin-right: 4px;

            margin-bottom: 4px;

            font-weight: 600;
        }


        /* EDIT */

        .edit-btn {
            background-color: #456882;

            color: white;
        }

        .edit-btn:hover {
            background-color: #234C6A;

            color: white;
        }


        /* REPLACE */

        .replace-btn {
            background-color: #234C6A;

            color: white;
        }

        .replace-btn:hover {
            background-color: #1B3C53;

            color: white;
        }


        /* DELETE */

        .delete-btn {
            background-color: #dc3545;

            color: white;
        }

        .delete-btn:hover {
            background-color: #bb2d3b;

            color: white;
        }


        /* =========================
           BOOKING HISTORY INDICATOR
        ========================= */

        .history-text {
            display: block;

            color: #888;

            font-size: 11px;

            margin-top: 3px;
        }


        /* =========================
           EMPTY MESSAGE
        ========================= */

        .empty-message {
            text-align: center;

            padding: 40px;

            color: #456882;
        }


        /* =========================
           BACK BUTTON
        ========================= */

        .back-btn {
            display: inline-block;

            margin-top: 25px;

            background-color: #456882;

            color: white;

            text-decoration: none;

            padding: 10px 18px;

            border-radius: 8px;
        }

        .back-btn:hover {
            background-color: #234C6A;

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

            .page-header {
                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>
        Rakz Rental
    </h2>


    <!-- DASHBOARD -->

    <a href="{{ route('admin.dashboard') }}">
        Dashboard
    </a>


    <!-- BOOKINGS -->

    <a href="{{ route('admin.bookings') }}">
        Bookings
    </a>


    <!-- VEHICLES -->

    <a
        href="{{ route('admin.vehicles') }}"
        class="active"
    >
        Vehicles
    </a>


    <!-- PROFILE -->

    <a href="{{ route('profile.show') }}">
        Profile
    </a>

</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">

    <!-- HEADER -->

    <div class="page-header">

        <div>

            <h1>
                Vehicles
            </h1>

            <p>
                Manage the vehicles available for rental.
            </p>

        </div>


        <a
            href="{{ route('admin.vehicles.create') }}"
            class="add-btn"
        >
            + Add Vehicle
        </a>

    </div>


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


    <!-- VEHICLE TABLE -->

    <div class="table-card">

        <h4>
            Vehicle Fleet
        </h4>


        @if($vehicles->count() > 0)

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Registration
                            </th>

                            <th>
                                Vehicle
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Daily Rate
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($vehicles as $vehicle)

                            <tr>

                                <!-- NUMBER -->

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <!-- REGISTRATION -->

                                <td>

                                    <strong>
                                        {{ $vehicle->registration }}
                                    </strong>

                                </td>


                                <!-- VEHICLE -->

                                <td>

                                    {{ $vehicle->name }}

                                    @if($vehicle->bookings()->exists())

                                        <span class="history-text">
                                            Has booking history
                                        </span>

                                    @endif

                                </td>


                                <!-- CATEGORY -->

                                <td>
                                    {{ $vehicle->category }}
                                </td>


                                <!-- DAILY RATE -->

                                <td>
                                    ${{ number_format($vehicle->daily_rate, 2) }}
                                </td>


                                <!-- STATUS -->

                                <td>

                                    @if($vehicle->status === 'available')

                                        <span class="status-available">
                                            Available
                                        </span>

                                    @else

                                        <span class="status-unavailable">
                                            Unavailable
                                        </span>

                                    @endif

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <!-- EDIT -->

                                    <a
                                        href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </a>


                                    <!-- REPLACE -->

                                    <a
                                        href="{{ route('admin.vehicles.replacement', $vehicle) }}"
                                        class="action-btn replace-btn"
                                    >
                                        Replace
                                    </a>

                                <!-- RETIRE -->

                             <form
                                        action="{{ route('admin.vehicles.destroy', $vehicle) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to retire this vehicle? The vehicle record and booking history will be preserved.');"
                                    >
                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn delete-btn"
                                        >
                                            Retire
                                        </button>
                                </form>
                                
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-message">

                <h5>
                    No vehicles found
                </h5>

                <p>
                    Add a vehicle to your fleet to get started.
                </p>

            </div>

        @endif

    </div>


    <!-- BACK BUTTON -->

    <a
        href="{{ route('admin.dashboard') }}"
        class="back-btn"
    >
        ← Back to Dashboard
    </a>


</div>


</body>

</html>