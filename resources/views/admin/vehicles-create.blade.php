<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Vehicle - Rakz Rental</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #1B3C53;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #1B3C53;
            padding: 25px 18px;
            color: white;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .logo p {
            font-size: 12px;
            color: #D2C1B6;
        }

        .nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
        }

        .nav a:hover,
        .nav a.active {
            background: #234C6A;
        }

        .back-btn {
            margin-top: 25px;
            border-top: 1px solid #456882;
            padding-top: 20px;
        }

        .logout {
            position: absolute;
            bottom: 25px;
            left: 18px;
            right: 18px;
        }

        .logout button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #456882;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .logout button:hover {
            background: #234C6A;
        }

        .main {
            margin-left: 250px;
            padding: 40px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #666;
        }

        .form-card {
            background: white;
            max-width: 800px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #1B3C53;
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #456882;
        }

        .error {
            color: #842029;
            font-size: 13px;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .save-btn {
            background: #234C6A;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .save-btn:hover {
            background: #1B3C53;
        }

        .cancel-btn {
            background: #D2C1B6;
            color: #1B3C53;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 7px;
            font-weight: bold;
        }

        .cancel-btn:hover {
            background: #456882;
            color: white;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="logo">
        <h2>Rakz Rental</h2>
        <p>Admin Portal</p>
    </div>

    <nav class="nav">

        <a href="{{ route('admin.dashboard') }}">
            Dashboard
        </a>

        <a href="{{ route('admin.bookings') }}">
            Bookings
        </a>

        <a href="{{ route('admin.vehicles') }}" class="active">
            Vehicles
        </a>

        <a href="{{ route('profile.show') }}">
            Profile
        </a>

    </nav>

    <div class="back-btn">
        <a href="{{ route('admin.vehicles') }}"
           style="color:white; text-decoration:none;">
            ← Back to Vehicles
        </a>
    </div>

    <div class="logout">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Logout
            </button>
        </form>

    </div>

</aside>


<!-- MAIN CONTENT -->
<main class="main">

    <div class="page-header">

        <h1>Add Vehicle</h1>

        <p>
            Add a new vehicle to the Rakz Rental fleet.
        </p>

    </div>


    <div class="form-card">

        @if ($errors->any())

            <div style="
                background:#f8d7da;
                color:#842029;
                padding:15px;
                border-radius:8px;
                margin-bottom:20px;
            ">

                <strong>Please correct the following errors:</strong>

                <ul style="margin-top:10px; margin-left:20px;">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('admin.vehicles.store') }}"
        >

            @csrf


            <!-- REGISTRATION -->
            <div class="form-group">

                <label for="registration">
                    Registration Number
                </label>

                <input
                    type="text"
                    id="registration"
                    name="registration"
                    value="{{ old('registration') }}"
                    placeholder="e.g. LR5360"
                    required
                >

                @error('registration')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <!-- VEHICLE NAME -->
            <div class="form-group">

                <label for="name">
                    Vehicle Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Toyota Corolla"
                    required
                >

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <!-- CATEGORY -->
            <div class="form-group">

                <label for="category">
                    Category
                </label>

                <select
                    id="category"
                    name="category"
                    required
                >

                    <option value="">
                        Select Category
                    </option>

                    <option value="Sedan"
                        {{ old('category') == 'Sedan' ? 'selected' : '' }}>
                        Sedan
                    </option>

                    <option value="SUV"
                        {{ old('category') == 'SUV' ? 'selected' : '' }}>
                        SUV
                    </option>

                    <option value="MPV"
                        {{ old('category') == 'MPV' ? 'selected' : '' }}>
                        MPV
                    </option>

                    <option value="Van"
                        {{ old('category') == 'Van' ? 'selected' : '' }}>
                        Van
                    </option>

                    <option value="Pickup"
                        {{ old('category') == 'Pickup' ? 'selected' : '' }}>
                        Pickup
                    </option>

                    <option value="Hatchback"
                        {{ old('category') == 'Hatchback' ? 'selected' : '' }}>
                        Hatchback
                    </option>

                    <option value="Wagon"
                        {{ old('category') == 'Wagon' ? 'selected' : '' }}>
                        Wagon
                    </option>

                </select>

                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <!-- DAILY RATE -->
            <div class="form-group">

                <label for="daily_rate">
                    Daily Rental Rate ($)
                </label>

                <input
                    type="number"
                    id="daily_rate"
                    name="daily_rate"
                    value="{{ old('daily_rate') }}"
                    placeholder="e.g. 150"
                    min="0"
                    step="0.01"
                    required
                >

                @error('daily_rate')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <!-- STATUS -->
            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                >

                    <option value="available"
                        {{ old('status', 'available') == 'available' ? 'selected' : '' }}>
                        Available
                    </option>

                    <option value="unavailable"
                        {{ old('status') == 'unavailable' ? 'selected' : '' }}>
                        Unavailable
                    </option>

                </select>

                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>


            <!-- BUTTONS -->
            <div class="form-actions">

                <button type="submit" class="save-btn">
                    Save Vehicle
                </button>

                <a
                    href="{{ route('admin.vehicles') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</main>

</body>
</html>