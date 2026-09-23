<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rakz Rental - Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #D2C1B6;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 450px;
            border: none;
            border-radius: 15px;
        }

        .brand {
            color: #1B3C53;
            font-size: 30px;
            font-weight: bold;
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

<div class="auth-container">

    <div class="card auth-card shadow">

        <div class="card-body p-5">

            <div class="text-center mb-4">

                <div class="brand">
                    Rakz Rental
                </div>

                <p class="text-muted">
                    Login to your account
                </p>

            </div>


            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>

            @endif


            @if($errors->any())

                <div class="alert alert-danger">

                    @foreach($errors->all() as $error)

                        <div>{{ $error }}</div>

                    @endforeach

                </div>

            @endif


            <form method="POST" action="{{ route('login.submit') }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                    >

                </div>


                <div class="mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-rakz w-100"
                >
                    Login
                </button>

            </form>


            <div class="text-center mt-4">

                <p>
                    Don't have an account?
                    <a href="{{ route('register') }}">
                        Sign Up
                    </a>
                </p>

            </div>

        </div>

    </div>

</div>

</body>

</html>