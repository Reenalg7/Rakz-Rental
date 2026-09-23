<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile - Rakz Rental</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #D2C1B6;
            color: #1B3C53;
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
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            font-size: 15px;
            transition: 0.2s;
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

        .page-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #1B3C53;
        }

        /* =========================
           PROFILE CARD
        ========================= */

        .profile-card {
            background-color: white;
            border-radius: 12px;
            padding: 35px;
            max-width: 800px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.10);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #1B3C53;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
        }

        .profile-header h2 {
            margin: 0;
            color: #1B3C53;
            font-size: 25px;
        }

        .profile-header p {
            margin: 5px 0 0;
            color: #456882;
        }

        /* =========================
           PROFILE INFORMATION
        ========================= */

        .profile-info {
            margin-top: 20px;
        }

        .info-row {
            display: flex;
            padding: 16px 0;
            border-bottom: 1px solid #ddd;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 180px;
            font-weight: bold;
            color: #1B3C53;
        }

        .info-value {
            color: #456882;
        }

        /* =========================
           ACTION BUTTONS
        ========================= */

        .profile-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            align-items: center;
        }

        .edit-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #234C6A;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            transition: 0.2s;
        }

        .edit-btn:hover {
            background-color: #1B3C53;
            color: white;
        }

        .logout-profile-btn {
            padding: 12px 24px;
            background-color: #1B3C53;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .logout-profile-btn:hover {
            background-color: #456882;
        }

        /* =========================
           SUCCESS MESSAGE
        ========================= */

        .alert-success {
            max-width: 800px;
            margin-bottom: 20px;
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

            .info-row {
                flex-direction: column;
                gap: 5px;
            }

            .info-label {
                width: auto;
            }

            .profile-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .edit-btn,
            .logout-profile-btn {
                width: 100%;
                text-align: center;
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

            .profile-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    {{-- =========================
         SIDEBAR
    ========================= --}}

    <div class="sidebar">

        <div class="brand">
            Rakz Rental
        </div>

        {{-- ADMIN NAVIGATION --}}
        @if(auth()->user()->role === 'admin')

            <a href="{{ route('admin.dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('admin.bookings') }}">
                Bookings
            </a>

            <a href="{{ route('admin.vehicles') }}">
                Vehicles
            </a>

            <a href="{{ route('profile.show') }}" class="active">
                Profile
            </a>

        {{-- CUSTOMER NAVIGATION --}}
        @else

            <a href="{{ route('vehicles.index') }}">
                Vehicles
            </a>

            <a href="{{ route('bookings.index') }}">
                Booking History
            </a>

            <a href="{{ route('profile.show') }}" class="active">
                Profile
            </a>

        @endif

    </div>


    {{-- =========================
         MAIN CONTENT
    ========================= --}}

    <div class="main-content">

        <h1 class="page-title">
            My Profile
        </h1>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        {{-- PROFILE CARD --}}
        <div class="profile-card">

            {{-- PROFILE HEADER --}}
            <div class="profile-header">

                <div class="profile-icon">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <h2>
                        {{ auth()->user()->name }}
                    </h2>

                    <p>
                        {{ auth()->user()->email }}
                    </p>
                </div>

            </div>


            {{-- PROFILE INFORMATION --}}
            <div class="profile-info">

                <div class="info-row">

                    <div class="info-label">
                        Full Name
                    </div>

                    <div class="info-value">
                        {{ auth()->user()->name }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Email Address
                    </div>

                    <div class="info-value">
                        {{ auth()->user()->email }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Account Type
                    </div>

                    <div class="info-value">

                        @if(auth()->user()->role === 'admin')
                            Administrator
                        @else
                            Customer
                        @endif

                    </div>

                </div>

            </div>


            {{-- =========================
                 PROFILE ACTIONS
            ========================= --}}

            <div class="profile-actions">

                {{-- EDIT PROFILE --}}
                <a href="{{ route('profile.edit') }}" class="edit-btn">
                    Edit Profile
                </a>


                {{-- LOGOUT --}}
                <form action="{{ route('logout') }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to logout?');">

                    @csrf

                    <button type="submit" class="logout-profile-btn">
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>
</html>