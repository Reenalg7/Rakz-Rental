<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rakz Rental') · Rakz Rental</title>

    <!-- Tailwind CDN keeps setup for the demo simple (university project). -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Apply saved theme BEFORE paint to avoid a flash of dark mode.
         On first visit (no stored key), fall back to the OS/browser
         `prefers-color-scheme` so new users get their system theme. -->
    <script>
       
        (function(){
            document.documentElement.setAttribute('data-theme', 'dark');
        })();

            </script>

    <style>
        /* Dark theme (default) */
        :root, [data-theme="dark"] {
            --rz-primary: #D4AF37;
            --rz-bg: #0B0F19;
            --rz-surface: #1F2937;
            --rz-text: #F9FAFB;
            --rz-muted: #9CA3AF;
            --rz-card-bg: rgba(31,41,55,0.6);
            --rz-card-border: rgba(255,255,255,0.08);
            --rz-input-bg: rgba(0,0,0,0.3);
            --rz-input-border: rgba(255,255,255,0.1);
            --rz-nav-bg: rgba(0,0,0,0.4);
            --rz-nav-border: rgba(255,255,255,0.1);
            --rz-ghost-border: rgba(255,255,255,0.15);
        }
        /* Light theme — friendlier for older guests */
        [data-theme="light"] {
            --rz-primary: #A17B16;      /* darker gold for AA contrast on white */
            --rz-bg: #F8FAFC;
            --rz-surface: #FFFFFF;
            --rz-text: #0F172A;
            --rz-muted: #64748B;
            --rz-card-bg: #FFFFFF;
            --rz-card-border: #E5E7EB;
            --rz-input-bg: #FFFFFF;
            --rz-input-border: #D1D5DB;
            --rz-nav-bg: rgba(255,255,255,0.9);
            --rz-nav-border: #E5E7EB;
            --rz-ghost-border: #D1D5DB;
        }
        html, body { background: var(--rz-bg); color: var(--rz-text); font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.01em; }
        .rz-primary { color: var(--rz-primary); }
        .rz-bg-primary { background-color: var(--rz-primary); color: #FFFFFF; }
        .rz-card { background: var(--rz-card-bg); backdrop-filter: blur(12px); border: 1px solid var(--rz-card-border); border-radius: 12px; }
        .rz-input { background: var(--rz-input-bg); border: 1px solid var(--rz-input-border); color: var(--rz-text); }
        .rz-input:focus { outline: none; border-color: var(--rz-primary); box-shadow: 0 0 0 3px rgba(212,175,55,0.15); }
        .rz-btn { background: var(--rz-primary); color: #FFFFFF; font-weight: 600; transition: transform .15s, box-shadow .15s; }
        [data-theme="dark"] .rz-btn { color: #0B0F19; }
        .rz-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(212,175,55,0.25); }
        .rz-btn-ghost { border: 1px solid var(--rz-ghost-border); color: var(--rz-text); }
        .rz-btn-ghost:hover { border-color: var(--rz-primary); color: var(--rz-primary); }
        .rz-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .rz-nav { background: var(--rz-nav-bg); border-bottom: 1px solid var(--rz-nav-border); }
        .rz-footer { background: var(--rz-nav-bg); border-top: 1px solid var(--rz-nav-border); color: var(--rz-muted); }

        /* White calendar icon on the booking date fields */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: brightness(0) invert(1);
            opacity: 1;
            cursor: pointer;
        }

    </style>
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="rz-nav backdrop-blur-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <a href="{{ route('home') }}" class="flex items-center gap-2" data-testid="nav-logo">
            <i class="fa-solid fa-car-side rz-primary text-2xl"></i>
            <span class="text-xl font-bold tracking-tight">Rakz <span class="rz-primary">Rental</span></span>
        </a>

        <div class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="hover:rz-primary transition" data-testid="nav-home">Home</a>
            <a href="{{ route('customer.vehicles.index') }}" class="hover:rz-primary transition" data-testid="nav-vehicles">Vehicles</a>
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('customer.favorites.index') }}" class="hover:rz-primary transition" data-testid="nav-favorites">
                        <i class="fa-regular fa-heart"></i> Favorites
                    </a>
                    <a href="{{ route('customer.bookings.index') }}" class="hover:rz-primary transition" data-testid="nav-my-bookings">My Bookings</a>
                    <a href="{{ route('customer.notifications.index') }}" class="hover:rz-primary transition" data-testid="nav-notifications">
                        <i class="fa-regular fa-bell"></i>
                    </a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:rz-primary transition" data-testid="nav-admin">Admin</a>
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-3">
            @auth
                <span class="hidden sm:inline text-sm text-gray-400">Hi, {{ auth()->user()->name }}</span>
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('customer.profile.edit') }}" class="text-sm rz-btn-ghost px-3 py-1.5 rounded-md" data-testid="nav-profile">Profile</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm rz-btn-ghost px-3 py-1.5 rounded-md" data-testid="nav-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm rz-btn-ghost px-3 py-1.5 rounded-md" data-testid="nav-login">Login</a>
                <a href="{{ route('register') }}" class="text-sm rz-btn px-3 py-1.5 rounded-md" data-testid="nav-register">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Flash messages -->
<div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-6">
    @if(session('success'))
        <div class="rz-card border-l-4 border-emerald-400 text-emerald-200 px-4 py-3 mb-4" data-testid="flash-success">
            <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rz-card border-l-4 border-red-400 text-red-200 px-4 py-3 mb-4" data-testid="flash-error">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rz-card border-l-4 border-amber-400 text-amber-200 px-4 py-3 mb-4" data-testid="flash-errors">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif
</div>

<main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
    @yield('content')
</main>

<footer class="rz-footer mt-16">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row justify-between gap-4 text-sm">
        <p>&copy; {{ date('Y') }} Rakz Rental. University project demonstration.</p>
        <p>Built with Laravel · PHP · MySQL</p>
    </div>
</footer>

<script>
    // Theme toggle — syncs <html data-theme=...> and the two FA icons.
    (function(){
        var btn = document.getElementById('themeToggle');
        var dark = document.getElementById('themeIconDark');
        var light = document.getElementById('themeIconLight');
        function apply(t){
            document.documentElement.setAttribute('data-theme', t);
            if (t === 'light') { dark.classList.add('hidden'); light.classList.remove('hidden'); }
            else { light.classList.add('hidden'); dark.classList.remove('hidden'); }
        }
        apply(document.documentElement.getAttribute('data-theme') || 'dark');
        btn.addEventListener('click', function(){
            var current = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            try { localStorage.setItem('rz-theme', current); } catch(e) {}
            apply(current);
        });

    })();
</script>

@stack('scripts')
</body>
</html>
