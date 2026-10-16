<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Rakz Rental</title>
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
        h1, h2, h3 { letter-spacing: -0.01em; color: var(--rz-ink); }

        :focus-visible { outline: 2px solid var(--rz-primary); outline-offset: 2px; }

        .rz-primary { color: var(--rz-primary); }
        .rz-link { color: var(--rz-primary); font-weight: 500; }
        .rz-link:hover { color: var(--rz-primary-dark); text-decoration: underline; text-underline-offset: 3px; }
        .rz-bg-primary { background-color: var(--rz-primary); color: #FFFFFF; }

        /* Surfaces */
        .rz-card { background: #FFFFFF; border: 1px solid var(--rz-line); border-radius: 8px; }

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

        /* Tables */
        .rz-table th { font-size: 0.8125rem; font-weight: 600; color: var(--rz-muted); background: var(--rz-snow); }
        .rz-table td, .rz-table th { padding: 0.75rem 1rem; border-bottom: 1px solid var(--rz-tint); text-align: left; }
        .rz-table thead th { border-bottom: 1px solid var(--rz-line); }
        .rz-table tbody tr:hover { background: var(--rz-snow); }
        .rz-table tbody tr:last-child td { border-bottom: 0; }

        /* Shell */
        .rz-sidebar { background: var(--rz-snow); border-right: 1px solid var(--rz-line); }
        .rz-topbar { background: #FFFFFF; border-bottom: 1px solid var(--rz-line); }
        .rz-nav-link { color: var(--rz-ink); border-left: 3px solid transparent; transition: background-color .15s; }
        .rz-nav-link:hover { background: var(--rz-tint); }
        .rz-nav-link.active { background: var(--rz-tint); border-left-color: var(--rz-primary); color: var(--rz-primary-dark); font-weight: 500; }
        .rz-logo-mark { width: 2rem; height: 2rem; border-radius: 6px; background: var(--rz-primary); color: #FFFFFF; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; }
        .rz-pill { display: inline-block; white-space: nowrap; padding: 0.375rem 0.75rem; border: 1px solid var(--rz-line); border-radius: 9999px; font-size: 0.8125rem; color: var(--rz-ink); background: #FFFFFF; }
        .rz-pill.active { background: var(--rz-primary); border-color: var(--rz-primary); color: #FFFFFF; }

        nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; display: inline; }

        .rz-skip { position: absolute; left: -9999px; top: 0; background: var(--rz-primary); color: #FFFFFF; padding: 0.5rem 1rem; z-index: 100; border-radius: 0 0 6px 0; }
        .rz-skip:focus { left: 0; }

        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
    @stack('head')
</head>
<body class="min-h-screen flex">
<a href="#main" class="rz-skip">Skip to content</a>

@php
    $r = request()->route()->getName();
    $adminLinks = [
        ['admin.dashboard',    'admin-nav-dashboard',    'Dashboard',    'fa-solid fa-chart-line',        fn($r) => $r === 'admin.dashboard'],
        ['admin.vehicles.index',     'admin-nav-vehicles',     'Vehicles',     'fa-solid fa-car',               fn($r) => str_starts_with($r,'admin.vehicles')],
        ['admin.categories.index',   'admin-nav-categories',   'Categories',   'fa-solid fa-tags',              fn($r) => str_starts_with($r,'admin.categories')],
        ['admin.bookings.index',     'admin-nav-bookings',     'Bookings',     'fa-regular fa-calendar-check',  fn($r) => str_starts_with($r,'admin.bookings')],
        ['admin.replacements.index', 'admin-nav-replacements', 'Replacements', 'fa-solid fa-shuffle',           fn($r) => str_starts_with($r,'admin.replacements')],
        ['admin.customers.index',    'admin-nav-customers',    'Customers',    'fa-solid fa-users',             fn($r) => str_starts_with($r,'admin.customers')],
        ['admin.reports.index',      'admin-nav-reports',      'Reports',      'fa-solid fa-chart-pie',         fn($r) => str_starts_with($r,'admin.reports')],
        ['admin.audit_logs.index',   'admin-nav-audit',        'Audit Log',    'fa-solid fa-shield-halved',     fn($r) => str_starts_with($r,'admin.audit_logs')],
    ];
@endphp

<!-- Sidebar -->
<aside class="w-64 shrink-0 rz-sidebar min-h-screen hidden md:flex md:flex-col" aria-label="Admin">
    <div class="px-6 py-5 border-b border-line">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <span class="rz-logo-mark" aria-hidden="true">R</span>
            <div>
                <div class="font-semibold leading-tight">Rakz Rental</div>
                <div class="text-xs text-muted">Admin Portal</div>
            </div>
        </a>
    </div>
    <nav class="flex-1 py-4 text-sm">
        @foreach($adminLinks as [$route, $testid, $label, $icon, $isActive])
            <a href="{{ route($route) }}" class="rz-nav-link flex items-center gap-3 px-5 py-2.5 {{ $isActive($r) ? 'active' : '' }}" data-testid="{{ $testid }}" @if($isActive($r)) aria-current="page" @endif>
                <i class="{{ $icon }} w-4 text-center text-muted" aria-hidden="true"></i>{{ $label }}
            </a>
        @endforeach
    </nav>
    <div class="p-4 border-t border-line">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full rz-btn-ghost py-2 text-sm" data-testid="admin-logout">Logout</button>
        </form>
    </div>
</aside>

<div class="flex-1 min-w-0">
    <!-- Topbar -->
    <header class="rz-topbar px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-xl font-semibold truncate">@yield('page_title', 'Admin')</h1>
            <p class="text-sm text-muted">@yield('page_subtitle', 'Rakz Rental administration')</p>
        </div>
        <div class="flex items-center gap-3 text-sm text-muted shrink-0">
            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                @csrf
                <button class="rz-btn-ghost px-3 py-1.5 text-sm">Logout</button>
            </form>
        </div>
    </header>

    <!-- Mobile navigation -->
    <nav class="md:hidden flex gap-2 overflow-x-auto px-4 py-3 border-b border-line bg-snow" aria-label="Admin sections">
        @foreach($adminLinks as [$route, $testid, $label, $icon, $isActive])
            <a href="{{ route($route) }}" class="rz-pill {{ $isActive($r) ? 'active' : '' }}" @if($isActive($r)) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>

    <main id="main" class="p-4 sm:p-6">
        @if(session('success'))
            <div class="rz-alert rz-alert-success" role="status" data-testid="admin-flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rz-alert rz-alert-error" role="alert" data-testid="admin-flash-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="rz-alert rz-alert-warn" role="alert"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
