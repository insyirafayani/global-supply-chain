<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    @hasSection('title')
        @yield('title') – GERIP
    @else
        GERIP – Global Export Risk Intelligence Platform
    @endif
</title>

<meta name="description" content="GERIP – Global Export Risk Intelligence Platform. Real-time trade risk monitoring, country intelligence, currency tracking, and supply chain analytics.">

@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])

{{-- Bootstrap 5 --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

{{-- Leaflet Map --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

{{-- Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

/* =====================
   GLOBAL RESET & BASE
   ===================== */

*, *::before, *::after {
    box-sizing: border-box;
}

body {
    background: #020617 !important;
    color: #f8fafc !important;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    margin: 0;
    overflow-x: hidden;
}

/* =====================
   SIDEBAR
   ===================== */

.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    background: #0a1628;
    left: 0;
    top: 0;
    padding: 0;
    border-right: 1px solid #1e293b;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    z-index: 1000;
}

.sidebar-header {
    padding: 24px 20px 20px;
    border-bottom: 1px solid #1e293b;
    flex-shrink: 0;
}

.logo {
    font-size: 22px;
    font-weight: 800;
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.5px;
}

.logo-sub {
    display: block;
    font-size: 10px;
    color: #475569;
    margin-top: 4px;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    -webkit-text-fill-color: #475569;
}

.sidebar-section-label {
    padding: 16px 20px 6px;
    font-size: 10px;
    font-weight: 600;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    flex-shrink: 0;
}

.menu {
    padding: 0 12px;
    flex-shrink: 0;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    margin-bottom: 2px;
    border-radius: 10px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
}

.menu a .menu-icon {
    font-size: 16px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}

.menu a:hover {
    background: #1e293b;
    color: #f1f5f9;
    transform: translateX(3px);
}

.menu a.active {
    background: linear-gradient(135deg, rgba(37,99,235,0.25), rgba(6,182,212,0.15));
    color: #38bdf8;
    border: 1px solid rgba(56,189,248,0.2);
}

.menu a.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: linear-gradient(180deg, #2563eb, #06b6d4);
    border-radius: 0 3px 3px 0;
}

.menu-badge {
    margin-left: auto;
    background: rgba(37,99,235,0.3);
    color: #38bdf8;
    font-size: 10px;
    padding: 1px 6px;
    border-radius: 8px;
    font-weight: 600;
}

.sidebar-divider {
    height: 1px;
    background: #1e293b;
    margin: 10px 20px;
}

.sidebar-footer {
    padding: 12px;
    border-top: 1px solid #1e293b;
    margin-top: auto;
    flex-shrink: 0;
}

/* =====================
   MAIN CONTENT
   ===================== */

.content {
    margin-left: 260px;
    padding: 30px;
    min-height: 100vh;
}

/* =====================
   TOPBAR
   ===================== */

.topbar {
    height: 62px;
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    margin-bottom: 28px;
}

.topbar-title {
    font-size: 15px;
    font-weight: 600;
    color: #f8fafc;
}

.topbar-meta {
    display: flex;
    align-items: center;
    gap: 14px;
}

.topbar-badge {
    background: rgba(56,189,248,0.1);
    border: 1px solid rgba(56,189,248,0.2);
    color: #38bdf8;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: 500;
}

/* =====================
   CARDS
   ===================== */

.card-dark {
    background: #0f172a !important;
    border: 1px solid #1e293b !important;
    border-radius: 16px;
    color: #f8fafc !important;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card-dark::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 16px;
    padding: 1px;
    background: linear-gradient(135deg, #2563eb, #06b6d4, #22c55e);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.card-dark:hover::before {
    opacity: 1;
}

.card-dark:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4),
                0 0 30px rgba(37, 99, 235, 0.1);
    border-color: rgba(56,189,248,0.15) !important;
}

.dashboard-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.dashboard-card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 24px 50px rgba(37, 99, 235, 0.2);
}

/* =====================
   FORMS
   ===================== */

.form-control,
.form-select {
    background: #0f172a !important;
    border: 1px solid #334155 !important;
    color: #f8fafc !important;
    border-radius: 10px;
    font-size: 13.5px;
}

.form-control:focus,
.form-select:focus {
    background: #0f172a !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15) !important;
    color: #f8fafc !important;
}

.form-control::placeholder {
    color: #475569;
}

/* =====================
   NAVIGATION PILLS
   ===================== */

.nav-pills .nav-link {
    color: #64748b;
    border-radius: 10px;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #2563eb, #06b6d4);
    color: white;
}

.nav-pills .nav-link:hover:not(.active) {
    background: #1e293b;
    color: #f1f5f9;
}

/* =====================
   TABLES
   ===================== */

.table-dark {
    --bs-table-bg: #0f172a;
    --bs-table-border-color: #1e293b;
    --bs-table-hover-bg: #1e293b;
    border-radius: 12px;
    overflow: hidden;
}

.table-dark thead th {
    background: #020617;
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 12px 16px;
    border-bottom: 1px solid #1e293b;
}

/* =====================
   BADGES
   ===================== */

.badge {
    font-weight: 500;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 6px;
}

/* =====================
   SCROLLBAR
   ===================== */

::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #0a1628;
}

::-webkit-scrollbar-thumb {
    background: #1e293b;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #334155;
}

/* =====================
   FLAG IMAGE
   ===================== */

.country-flag {
    object-fit: cover;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(56,189,248,0.3);
}

/* =====================
   LOADING STATE
   ===================== */

.loading-pulse {
    background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
    background-size: 200% 100%;
    animation: pulse 1.5s ease-in-out infinite;
    border-radius: 8px;
}

@keyframes pulse {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* =====================
   EMPTY STATE
   ===================== */

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #475569;
}

.empty-state-icon {
    font-size: 56px;
    margin-bottom: 16px;
    opacity: 0.4;
}

</style>

</head>

<body>


<!-- =====================
     SIDEBAR
     ===================== -->

<div class="sidebar">

    <div class="sidebar-header">
        <div class="logo">
            🌍 GERIP
            <span class="logo-sub">Global Export Risk Intelligence</span>
        </div>
    </div>


    {{-- MAIN MENU --}}
    <div class="sidebar-section-label">Main</div>
    <div class="menu">
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.index') : route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') || request()->routeIs('admin.index') ? 'active' : '' }}">
            <span class="menu-icon">📊</span>
            Dashboard
        </a>

    </div>

    @if(Auth::user()->role === 'user')


    {{-- INTELLIGENCE --}}
    <div class="sidebar-section-label">Intelligence</div>
    <div class="menu">

        <a href="{{ route('countries.index') }}"
           class="{{ request()->routeIs('countries.*') ? 'active' : '' }}">
            <span class="menu-icon">🌎</span>
            Country Monitor
        </a>

        <a href="{{ route('risk.analytics') }}"
           class="{{ request()->routeIs('risk.analytics') ? 'active' : '' }}">
            <span class="menu-icon">⚠️</span>
            Risk Analytics
        </a>

        <a href="{{ route('weather.index') }}"
           class="{{ request()->routeIs('weather.index') ? 'active' : '' }}">
            <span class="menu-icon">☁️</span>
            Weather Monitoring
        </a>

        <a href="{{ route('currency.index') }}"
           class="{{ request()->routeIs('currency.index') ? 'active' : '' }}">
            <span class="menu-icon">💱</span>
            Currency Intelligence
        </a>

        <a href="{{ route('news.index') }}"
           class="{{ request()->routeIs('news.index') ? 'active' : '' }}">
            <span class="menu-icon">📰</span>
            Global News
        </a>

        <a href="{{ route('ports.index') }}"
           class="{{ request()->routeIs('ports.index') ? 'active' : '' }}">
            <span class="menu-icon">⚓</span>
            Port Monitoring
        </a>

    </div>


    {{-- TOOLS --}}
    <div class="sidebar-section-label">Tools</div>
    <div class="menu">

        <a href="{{ route('analytics.index') }}"
           class="{{ request()->routeIs('analytics.*') ? 'active' : '' }}">
            <span class="menu-icon">📈</span>
            Analytics
        </a>

        <a href="{{ route('comparison.index') }}"
           class="{{ request()->routeIs('comparison.*') ? 'active' : '' }}">
            <span class="menu-icon">⚖️</span>
            Country Comparison
        </a>

        <a href="{{ route('watchlist.index') }}"
           class="{{ request()->routeIs('watchlist.*') ? 'active' : '' }}">
            <span class="menu-icon">⭐</span>
            Watchlist
        </a>

    </div>
    @endif

    @if(Auth::user()->role === 'admin')
    {{-- ADMIN PANEL --}}
    <div class="sidebar-section-label">Management</div>
    <div class="menu">

        <a href="{{ route('admin.users') }}"
           class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <span class="menu-icon">👥</span>
            User Management
        </a>

        <a href="{{ route('admin.ports') }}"
           class="{{ request()->routeIs('admin.ports') ? 'active' : '' }}">
            <span class="menu-icon">⚓</span>
            Port Dataset
        </a>

        <a href="{{ route('admin.articles') }}"
           class="{{ request()->routeIs('admin.articles') ? 'active' : '' }}">
            <span class="menu-icon">📝</span>
            Analysis Articles
        </a>

    </div>
    @endif


    <div class="sidebar-divider"></div>


    {{-- USER --}}
    <div class="sidebar-footer">
        <div class="menu">

            <a href="{{ route('profile.edit') }}"
               class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="menu-icon">👤</span>
                {{ Str::limit(Auth::user()->name, 18) }}
                <span class="menu-badge">{{ Auth::user()->role }}</span>
            </a>


            <a href="#" style="color:#ef4444 !important;"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-icon">🚪</span>
                Logout
            </a>

            <form id="logout-form" method="POST"
                  action="{{ route('logout') }}" style="display:none">
                @csrf
            </form>

        </div>
    </div>

</div>


<!-- =====================
     MAIN CONTENT
     ===================== -->

<div class="content">

    {{-- BACK BUTTON (non-dashboard pages) --}}
    @if(!request()->routeIs('dashboard'))
    <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-3"
       style="border-color:#1e293b; color:#64748b; font-size:12px;">
        ← Back
    </a>
    @endif

    {{-- TOPBAR --}}
    <div class="topbar">

        <div class="topbar-title">
            🌍 GERIP &mdash; Global Export Risk Intelligence Platform
        </div>

        <div class="topbar-meta">

            <span class="topbar-badge" id="realtime-clock">
                🕐 {{ now()->timezone('Asia/Jakarta')->format('d M Y H:i:s') }}
            </span>

            <span class="topbar-badge">
                👤 {{ Auth::user()->name }}
            </span>

        </div>

    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4"
         style="background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3); color:#86efac; border-radius:12px;">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4"
         style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; border-radius:12px;">
        ❌ {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    {{-- PAGE CONTENT --}}
    {{ $slot }}


</div>


{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stack('scripts')

<script>
    function updateClock() {
        const clockElement = document.getElementById('realtime-clock');
        if (clockElement) {
            const now = new Date();
            const formatter = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const parts = formatter.formatToParts(now);
            const dateMap = {};
            parts.forEach(({ type, value }) => {
                dateMap[type] = value;
            });
            
            // Expected format: dd Mmm yyyy HH:mm:ss
            const timeString = `${dateMap.day} ${dateMap.month} ${dateMap.year} ${dateMap.hour}:${dateMap.minute}:${dateMap.second}`;
            clockElement.innerHTML = `🕐 ${timeString}`;
        }
    }
    
    setInterval(updateClock, 1000);
    updateClock(); // Initial call
</script>

</body>

</html>