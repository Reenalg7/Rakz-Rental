<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Rakz Rental - Sign Up</title>

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
            padding: 30px 15px;
        }

        .auth-card {
            width: 450px;
            border: none;
            border-radius: 18px;
        }

        .brand {
            color: #1B3C53;
            font-size: 30px;
            font-weight: bold;
        }

        .subtitle {
            color: #6c757d;
        }

        .form-label {
            color: #1B3C53;
            font-weight: 600;
        }

        .form-control {
            border-radius: 9px;
            padding: 11px 13px;
            border: 1px solid #d5d5d5;
        }

        .form-control:focus {
            border-color: #456882;
            box-shadow: 0 0 0 0.2rem rgba(69, 104, 130, 0.15);
        }

        .btn-rakz {
            background-color: #234C6A;
            color: white;
            border: none;
            border-radius: 9px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-rakz:hover {
            background-color: #1B3C53;
            color: white;
        }

        .password-help {
            background-color: #f7f4f2;
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 8px;
        }

        .password-help-title {
            color: #234C6A;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .password-help ul {
            margin: 0;
            padding-left: 18px;
        }

        .password-help li {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .login-link {
            color: #234C6A;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link:hover {
            color: #1B3C53;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

    </style>

</head>


<body>

<div class="auth-container">

    <div class="card auth-card shadow">

        <div class="card-body p-5">

            <!-- Header -->

            <div class="text-center mb-4">

                <div class="brand">
                    Rakz Rental
                </div>

                <p class="subtitle mb-0">
                    Create your customer account
                </p>

            </div>


            <!-- General Error -->

            @if(session('error'))

                <div class="alert alert-danger">

                    {{ session('error') }}

                </div>

            @endif


            <!-- Validation Errors -->

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


            <!-- Registration Form -->

            <form
                method="POST"
                action="{{ route('register.submit') }}"
            >

                @csrf


                <!-- Full Name -->

                <div class="mb-3">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


                <!-- Email -->

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                        required
                    >

                </div>


                <!-- Password -->

                <div class="mb-2">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Create a strong password"
                        required
                    >

                </div>


                <!-- Password Requirements -->

                <div class="password-help mb-3">

                    <div class="password-help-title">
                        Password must contain:
                    </div>

                    <ul>

                        <li>
                            At least 8 characters
                        </li>

                        <li>
                            At least one uppercase letter
                        </li>

                        <li>
                            At least one lowercase letter
                        </li>

                        <li>
                            At least one number
                        </li>

                        <li>
                            At least one special character
                        </li>

                    </ul>

                </div>


                <!-- Confirm Password -->

                <div class="mb-4">

                    <label
                        for="password_confirmation"
                        class="form-label"
                    >
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm your password"
                        required
                    >

                </div>


                <!-- Create Account -->

                <button
                    type="submit"
                    class="btn btn-rakz w-100"
                >
                    Create Account
                </button>

            </form>


            <!-- Login -->

            <div class="text-center mt-4">

                <p class="mb-0">

                    Already have an account?

                    <a
                        href="{{ route('login') }}"
                        class="login-link"
                    >
                        Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

</body>

</html>