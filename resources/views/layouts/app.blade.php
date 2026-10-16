<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rakz Rental') · Rakz Rental</title>

    <!-- Tailwind CDN keeps setup for the demo simple (university project). -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'SFMono-Regular', 'monospace'],
                    },
                    colors: {
                        brand: { DEFAULT: '#005691', dark: '#004A7C' },
                        tint: '#E8F1F5',
                        snow: '#FAFAFA',
                        ink: '#14232F',
                        muted: '#506576',
                        line: '#D4E2EB',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --rz-primary: #005691;
            --rz-primary-dark: #004A7C;
            --rz-tint: #E8F1F5;
            --rz-snow: #FAFAFA;
            --rz-bg: #FFFFFF;
            --rz-ink: #14232F;
            --rz-muted: #506576;
            --rz-line: #D4E2EB;
        }
        html, body { background: var(--rz-bg); color: var(--rz-ink); }
        body { font-family: 'IBM Plex Sans', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { letter-spacing: -0.01em; color: var(--rz-ink); }

        :focus-visible { outline: 2px solid var(--rz-primary); outline-offset: 2px; }

        .rz-primary { color: var(--rz-primary); }
        .rz-link { color: var(--rz-primary); font-weight: 500; }
        .rz-link:hover { color: var(--rz-primary-dark); text-decoration: underline; text-underline-offset: 3px; }
        .rz-bg-primary { background-color: var(--rz-primary); color: #FFFFFF; }

        /* Surfaces */
        .rz-card { background: #FFFFFF; border: 1px solid var(--rz-line); border-radius: 8px; }
        a.rz-card, .rz-card-hover { transition: border-color .15s; }
        a.rz-card:hover, .rz-card-hover:hover { border-color: var(--rz-primary); }

        /* Form controls */
        .rz-label { display: block; font-size: 0.8125rem; font-weight: 500; color: var(--rz-ink); }
        .rz-input { background: #FFFFFF; border: 1px solid #738EA2; color: var(--rz-ink); border-radius: 6px; }
        .rz-input::placeholder { color: #5F7384; }
        .rz-input:focus { outline: none; border-color: var(--rz-primary); box-shadow: 0 0 0 3px var(--rz-tint); }
        .rz-input[readonly] { background: var(--rz-snow); color: var(--rz-muted); }

        /* Buttons */
        .rz-btn { background: var(--rz-primary); color: #FFFFFF; font-weight: 500; border: 1px solid var(--rz-primary); border-radius: 6px; transition: background-color .15s, border-color .15s; display: inline-block; text-align: center; }
        .rz-btn:hover { background: var(--rz-primary-dark); border-color: var(--rz-primary-dark); }
        .rz-btn:disabled { background: #7FA6C2; border-color: #7FA6C2; }
        :where(.rz-btn-ghost) { color: var(--rz-primary-dark); }
        .rz-btn-ghost { background: #FFFFFF; border: 1px solid #738EA2; font-weight: 500; border-radius: 6px; transition: background-color .15s, border-color .15s; display: inline-block; text-align: center; }
        .rz-btn-ghost:hover { background: var(--rz-tint); border-color: var(--rz-primary); }

        /* Badges and registration plate */
        .rz-badge { display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        :where(.rz-badge) { background: var(--rz-tint); color: var(--rz-primary-dark); }
        .rz-plate { display: inline-block; font-family: 'IBM Plex Mono', ui-monospace, monospace; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.04em; color: var(--rz-primary-dark); background: #FFFFFF; border: 1.5px solid var(--rz-primary-dark); border-radius: 4px; padding: 0.0625rem 0.5rem; white-space: nowrap; }

        /* Alerts */
        .rz-alert { border: 1px solid var(--rz-line); border-left-width: 4px; border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.875rem; background: #FFFFFF; }
        .rz-alert-success { border-left-color: #1B7F4B; background: #F2FAF5; color: #14532D; }
        .rz-alert-error   { border-left-color: #B42318; background: #FEF3F2; color: #7A271A; }
        .rz-alert-warn    { border-left-color: #B54708; background: #FFFAEB; color: #7A2E0E; }

        /* Navigation */
        .rz-nav { background: #FFFFFF; border-bottom: 1px solid var(--rz-line); }
        .rz-navlink { color: var(--rz-ink); padding: 0.25rem 0; border-bottom: 2px solid transparent; transition: color .15s, border-color .15s; }
        .rz-navlink:hover { color: var(--rz-primary); }
        .rz-navlink.is-active { color: var(--rz-primary); border-bottom-color: var(--rz-primary); font-weight: 500; }
        .rz-mobile-link { display: block; padding: 0.625rem 0; border-bottom: 1px solid var(--rz-tint); color: var(--rz-ink); }
        .rz-mobile-link.is-active { color: var(--rz-primary); font-weight: 500; }
        .rz-logo-mark { width: 2rem; height: 2rem; border-radius: 6px; background: var(--rz-primary); color: #FFFFFF; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; }
        .rz-footer { background: var(--rz-snow); border-top: 1px solid var(--rz-line); color: var(--rz-muted); }

        /* Pagination (Laravel default markup) */
        nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; display: inline; }

        .rz-skip { position: absolute; left: -9999px; top: 0; background: var(--rz-primary); color: #FFFFFF; padding: 0.5rem 1rem; z-index: 100; border-radius: 0 0 6px 0; }
        .rz-skip:focus { left: 0; }

        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
<a href="#main" class="rz-skip">Skip to content</a>

@php $rn = request()->route()?->getName() ?? ''; @endphp

<!-- Navigation -->
<nav class="rz-nav sticky top-0 z-50" aria-label="Main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" data-testid="nav-logo">
            <span class="rz-logo-mark" aria-hidden="true">R</span>
            <span class="text-lg font-semibold tracking-tight">Rakz Rental</span>
        </a>

        <div class="hidden md:flex items-center gap-7 text-sm">
            <a href="{{ route('home') }}" class="rz-navlink {{ $rn === 'home' ? 'is-active' : '' }}" data-testid="nav-home">Home</a>
            <a href="{{ route('customer.vehicles.index') }}" class="rz-navlink {{ str_starts_with($rn, 'customer.vehicles') ? 'is-active' : '' }}" data-testid="nav-vehicles">Vehicles</a>
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('customer.favorites.index') }}" class="rz-navlink {{ str_starts_with($rn, 'customer.favorites') ? 'is-active' : '' }}" data-testid="nav-favorites">Favorites</a>
                    <a href="{{ route('customer.bookings.index') }}" class="rz-navlink {{ str_starts_with($rn, 'customer.bookings') ? 'is-active' : '' }}" data-testid="nav-my-bookings">My Bookings</a>
                    <a href="{{ route('customer.notifications.index') }}" class="rz-navlink {{ str_starts_with($rn, 'customer.notifications') ? 'is-active' : '' }}" data-testid="nav-notifications">Notifications</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="rz-navlink" data-testid="nav-admin">Admin</a>
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                <span class="hidden lg:inline text-sm text-muted">{{ auth()->user()->name }}</span>
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('customer.profile.edit') }}" class="hidden sm:inline-block text-sm rz-btn-ghost px-3 py-1.5" data-testid="nav-profile">Profile</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm rz-btn-ghost px-3 py-1.5" data-testid="nav-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm rz-btn-ghost px-3 py-1.5" data-testid="nav-login">Login</a>
                <a href="{{ route('register') }}" class="text-sm rz-btn px-3 py-1.5" data-testid="nav-register">Sign Up</a>
            @endauth

            <button type="button" id="navToggle" class="md:hidden rz-btn-ghost w-9 h-9 flex items-center justify-center"
                aria-label="Open menu" aria-expanded="false" aria-controls="mobileNav">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileNav" class="hidden md:hidden border-t border-line bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2 text-sm">
            <a href="{{ route('home') }}" class="rz-mobile-link {{ $rn === 'home' ? 'is-active' : '' }}">Home</a>
            <a href="{{ route('customer.vehicles.index') }}" class="rz-mobile-link {{ str_starts_with($rn, 'customer.vehicles') ? 'is-active' : '' }}">Vehicles</a>
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('customer.favorites.index') }}" class="rz-mobile-link {{ str_starts_with($rn, 'customer.favorites') ? 'is-active' : '' }}">Favorites</a>
                    <a href="{{ route('customer.bookings.index') }}" class="rz-mobile-link {{ str_starts_with($rn, 'customer.bookings') ? 'is-active' : '' }}">My Bookings</a>
                    <a href="{{ route('customer.notifications.index') }}" class="rz-mobile-link {{ str_starts_with($rn, 'customer.notifications') ? 'is-active' : '' }}">Notifications</a>
                    <a href="{{ route('customer.profile.edit') }}" class="rz-mobile-link {{ str_starts_with($rn, 'customer.profile') ? 'is-active' : '' }}">Profile</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="rz-mobile-link">Admin</a>
                @endif
            @endauth
        </div>
    </div>
</nav>

<!-- Flash messages -->
@if(session('success') || session('error') || $errors->any())
<div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-6" aria-live="polite">
    @if(session('success'))
        <div class="rz-alert rz-alert-success" role="status" data-testid="flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rz-alert rz-alert-error" role="alert" data-testid="flash-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="rz-alert rz-alert-warn" role="alert" data-testid="flash-errors">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif
</div>
@endif

<main id="main" class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
    @yield('content')
</main>

<footer class="rz-footer mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between gap-2 text-sm">
        <p>&copy; {{ date('Y') }} Rakz Rental. University project demonstration.</p>
        <p>Built with Laravel, PHP and MySQL</p>
    </div>
</footer>

<script>
    // Mobile navigation toggle.
    (function(){
        var btn = document.getElementById('navToggle');
        var menu = document.getElementById('mobileNav');
        if (!btn || !menu) return;
        btn.addEventListener('click', function(){
            var open = menu.classList.toggle('hidden') === false;
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        });
    })();
</script>

@stack('scripts')
</body>
</html>
