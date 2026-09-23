<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Edit Profile</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background-color: #D2C1B6;
        }

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

        .main-content {
            margin-left: 230px;
            padding: 40px;
        }

        .profile-card {
            max-width: 700px;
            border: none;
            border-radius: 15px;
        }

        .btn-rakz {
            background-color: #234C6A;
            color: white;
        }

        .btn-rakz:hover {
            background-color: #1B3C53;
            color: white;
        }

    </style>

</head>

<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <a
        href="{{ route('vehicles.index') }}"
        class="brand"
    >
        Rakz Rental
    </a>


    <a
        href="{{ route('vehicles.index') }}"
        class="nav-link"
    >
         Vehicles
    </a>


    <a
        href="{{ route('bookings.index') }}"
        class="nav-link"
    >
         Booking History
    </a>

    <a
        href="{{ route('profile.show') }}"
        class="nav-link active"
    >
         Profile
    </a>


    <div class="sidebar-bottom">

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="logout-btn"
            >
                ↪ Logout
            </button>

        </form>

    </div>

</div>


<!-- MAIN CONTENT -->

<div class="main-content">

    <div class="container-fluid">

        <h1
            class="fw-bold mb-2"
            style="color: #1B3C53;"
        >
            Edit Profile
        </h1>

        <p class="text-muted mb-4">
            Update your Rakz Rental account information.
        </p>


        <div class="card profile-card shadow">

            <div class="card-body p-5">


                @if($errors->any())

                    <div class="alert alert-danger">

                        @foreach($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                >

                    @csrf

                    @method('PUT')


                    <!-- NAME -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', auth()->user()->name) }}"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', auth()->user()->email) }}"
                            required
                        >

                    </div>


                    <hr class="my-4">


                   <h5
                        class="fw-bold mb-3"
                        style="color: #1B3C53;"
                    >
                        Security Verification
                    </h5>

                    <p class="text-muted">
                        Enter your current password to save changes to your profile.
                    </p>


                    <!-- CURRENT PASSWORD -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        >

                        @error('current_password')

                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <hr class="my-4">


                    <h5
                        class="fw-bold mb-3"
                        style="color: #1B3C53;"
                    >
                        Change Password
                    </h5>

                    <p class="text-muted">
                        Leave these fields empty if you do not want to change your password.
                    </p>


                    <!-- NEW PASSWORD -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                        >

                    </div>


                    <!-- CONFIRM NEW PASSWORD -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                        >

                    </div>

                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('profile.show') }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-rakz"
                        >
                            Save Changes
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>

</html>