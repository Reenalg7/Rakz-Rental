<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Rakz Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Apply saved admin theme BEFORE paint to avoid a flash.
         On first visit, honour the OS `prefers-color-scheme` so a daylight
         admin lands in light mode without touching the toggle. -->
    <script>
       
        (function(){
            document.documentElement.setAttribute('data-theme', 'dark');
        })();

    </script>
    <style>
        /* Dark (default) — ops dashboards look sharper this way. */
        :root, [data-theme="dark"] {
            --rz-primary: #D4AF37;
            --rz-bg: #0B0F19;
            --rz-surface: #111827;
            --rz-text: #F9FAFB;
            --rz-muted: #9CA3AF;
            --rz-card-bg: rgba(31,41,55,0.6);
            --rz-card-border: rgba(255,255,255,0.08);
            --rz-input-bg: rgba(0,0,0,0.3);
            --rz-input-border: rgba(255,255,255,0.1);
            --rz-sidebar-bg: rgba(0,0,0,0.6);
            --rz-sidebar-border: rgba(255,255,255,0.1);
            --rz-row-hover: rgba(255,255,255,0.05);
            --rz-row-active: rgba(255,255,255,0.1);
            --rz-ghost-border: rgba(255,255,255,0.15);
            --rz-table-border: rgba(255,255,255,0.06);
            --rz-btn-fg: #0B0F19;
        }
        /* Light — easier on the eyes during daytime shifts. */
        [data-theme="light"] {
            --rz-primary: #A17B16;
            --rz-bg: #F3F4F6;
            --rz-surface: #FFFFFF;
            --rz-text: #0F172A;
            --rz-muted: #64748B;
            --rz-card-bg: #FFFFFF;
            --rz-card-border: #E5E7EB;
            --rz-input-bg: #FFFFFF;
            --rz-input-border: #D1D5DB;
            --rz-sidebar-bg: #FFFFFF;
            --rz-sidebar-border: #E5E7EB;
            --rz-row-hover: #F3F4F6;
            --rz-row-active: #FEF3C7;
            --rz-ghost-border: #D1D5DB;
            --rz-table-border: #E5E7EB;
            --rz-btn-fg: #FFFFFF;
        }
        html, body { background: var(--rz-bg); color: var(--rz-text); font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Space Grotesk', sans-serif; }
        .rz-primary { color: var(--rz-primary); }
        .rz-bg-primary { background-color: var(--rz-primary); color: var(--rz-btn-fg); }
        .rz-card { background: var(--rz-card-bg); border: 1px solid var(--rz-card-border); border-radius: 12px; }
        .rz-input { background: var(--rz-input-bg); border: 1px solid var(--rz-input-border); color: var(--rz-text); }
        .rz-input:focus { outline: none; border-color: var(--rz-primary); box-shadow: 0 0 0 3px rgba(212,175,55,0.15); }
        .rz-btn { background: var(--rz-primary); color: var(--rz-btn-fg); font-weight: 600; }
        .rz-btn-ghost { border: 1px solid var(--rz-ghost-border); color: var(--rz-text); }
        .rz-table th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--rz-muted); }
        .rz-table td, .rz-table th { padding: 0.75rem 1rem; border-bottom: 1px solid var(--rz-table-border); }
        .rz-sidebar { background: var(--rz-sidebar-bg); border-right: 1px solid var(--rz-sidebar-border); }
        .rz-topbar { border-bottom: 1px solid var(--rz-sidebar-border); }
        .rz-nav-link { color: var(--rz-text); }
        .rz-nav-link:hover { background: var(--rz-row-hover); }
        .rz-nav-link.active { background: var(--rz-row-active); border-left: 2px solid var(--rz-primary); }
    </style>
</head>
<body class="min-h-screen flex">

<!-- Sidebar -->
<aside class="w-64 shrink-0 rz-sidebar min-h-screen hidden md:flex md:flex-col">
    <div class="px-6 py-5" style="border-bottom:1px solid var(--rz-sidebar-border);">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <i class="fa-solid fa-car-side rz-primary text-2xl"></i>
            <div>
                <div class="font-bold">Rakz Rental</div>
                <div class="text-xs" style="color: var(--rz-muted);">Admin Portal</div>
            </div>
        </a>
    </div>
    <nav class="flex-1 py-4 text-sm">
        @php $r = request()->route()->getName(); @endphp
        <a href="{{ route('admin.dashboard') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ $r === 'admin.dashboard' ? 'active' : '' }}" data-testid="admin-nav-dashboard"><i class="fa-solid fa-chart-line w-5"></i>Dashboard</a>
        <a href="{{ route('admin.vehicles.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.vehicles') ? 'active' : '' }}" data-testid="admin-nav-vehicles"><i class="fa-solid fa-car w-5"></i>Vehicles</a>
        <a href="{{ route('admin.categories.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.categories') ? 'active' : '' }}" data-testid="admin-nav-categories"><i class="fa-solid fa-tags w-5"></i>Categories</a>
        <a href="{{ route('admin.bookings.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.bookings') ? 'active' : '' }}" data-testid="admin-nav-bookings"><i class="fa-regular fa-calendar-check w-5"></i>Bookings</a>
        <a href="{{ route('admin.replacements.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.replacements') ? 'active' : '' }}" data-testid="admin-nav-replacements"><i class="fa-solid fa-shuffle w-5"></i>Replacements</a>
        <a href="{{ route('admin.customers.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.customers') ? 'active' : '' }}" data-testid="admin-nav-customers"><i class="fa-solid fa-users w-5"></i>Customers</a>
        <a href="{{ route('admin.reports.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.reports') ? 'active' : '' }}" data-testid="admin-nav-reports"><i class="fa-solid fa-chart-pie w-5"></i>Reports</a>
        <a href="{{ route('admin.audit_logs.index') }}" class="rz-nav-link flex items-center gap-3 px-6 py-2.5 {{ str_starts_with($r,'admin.audit_logs') ? 'active' : '' }}" data-testid="admin-nav-audit"><i class="fa-solid fa-shield-halved w-5"></i>Audit Log</a>
    </nav>
    <div class="p-4" style="border-top:1px solid var(--rz-sidebar-border);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full rz-btn-ghost rounded-md py-2 text-sm" data-testid="admin-logout"><i class="fa-solid fa-right-from-bracket mr-2"></i>Logout</button>
        </form>
    </div>
</aside>

<div class="flex-1">
    <!-- Topbar -->
    <header class="rz-topbar px-6 py-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold">@yield('page_title', 'Admin')</h1>
            <p class="text-xs" style="color: var(--rz-muted);">@yield('page_subtitle', 'Rakz Rental administration')</p>
        </div>
        <div class="flex items-center gap-3 text-sm" style="color: var(--rz-muted);">
            <span><i class="fa-regular fa-user mr-1"></i>{{ auth()->user()->name }}</span>
        </div>
    </header>

    <div class="p-6">
        @if(session('success'))
            <div class="rz-card border-l-4 border-emerald-400 text-emerald-200 px-4 py-3 mb-4" data-testid="admin-flash-success"><i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rz-card border-l-4 border-red-400 text-red-200 px-4 py-3 mb-4" data-testid="admin-flash-error"><i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="rz-card border-l-4 border-amber-400 text-amber-200 px-4 py-3 mb-4"><ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    // Admin theme toggle — separate localStorage key from the customer side
    // so admins can prefer dark while the same person browses the customer
    // area in light, or vice versa.
    (function(){
        var btn = document.getElementById('adminThemeToggle');
        var dark = document.getElementById('adminThemeIconDark');
        var light = document.getElementById('adminThemeIconLight');
        function apply(t){
            document.documentElement.setAttribute('data-theme', t);
            if (t === 'light') { dark.classList.add('hidden'); light.classList.remove('hidden'); }
            else { light.classList.add('hidden'); dark.classList.remove('hidden'); }
        }
        apply(document.documentElement.getAttribute('data-theme') || 'dark');
        btn.addEventListener('click', function(){
            var current = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            try { localStorage.setItem('rz-admin-theme', current); } catch(e) {}
            apply(current);
        });

    })();
</script>

</body>
</html>
