<x-dashboard-layout>

@section('title', 'Weather Monitoring')

<style>
.weather-wrapper { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
.kpi-card {
    background: linear-gradient(135deg,#0f172a,#1e293b);
    border: 1px solid #1e293b;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.kpi-card::after {
    content:''; position:absolute;
    top:-30px; right:-30px;
    width:100px; height:100px;
    border-radius:50%; opacity:0.07;
}
.kpi-card.kpi-danger  { border-color:rgba(239,68,68,0.35); }
.kpi-card.kpi-danger::after  { background:#ef4444; }
.kpi-card.kpi-warning { border-color:rgba(245,158,11,0.35); }
.kpi-card.kpi-warning::after { background:#f59e0b; }
.kpi-card.kpi-info    { border-color:rgba(56,189,248,0.35); }
.kpi-card.kpi-info::after    { background:#38bdf8; }
.kpi-card.kpi-success { border-color:rgba(34,197,94,0.35); }
.kpi-card.kpi-success::after { background:#22c55e; }
.kpi-card:hover { transform:translateY(-4px); }
.kpi-label { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:8px; }
.kpi-value { font-size:32px; font-weight:800; line-height:1; }
.kpi-sub   { font-size:11px; color:#64748b; margin-top:6px; }
.kpi-danger .kpi-value  { color:#ef4444; }
.kpi-warning .kpi-value { color:#f59e0b; }
.kpi-info .kpi-value    { color:#38bdf8; }
.kpi-success .kpi-value { color:#22c55e; }

.section-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:16px; padding:22px;
    margin-bottom:24px;
}
.section-title {
    font-size:15px; font-weight:700; color:#f8fafc;
    margin-bottom:18px; display:flex; align-items:center; gap:8px;
}
.badge-pill {
    font-size:10px; background:rgba(56,189,248,0.15);
    color:#38bdf8; border:1px solid rgba(56,189,248,0.25);
    padding:2px 8px; border-radius:20px; font-weight:600;
}
.page-title {
    font-size:24px; font-weight:800;
    background:linear-gradient(135deg,#38bdf8,#2563eb);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    background-clip:text;
}
.weather-status-hot     { background:rgba(239,68,68,0.12);  color:#ef4444; border:1px solid rgba(239,68,68,0.25); }
.weather-status-warm    { background:rgba(245,158,11,0.12); color:#f59e0b; border:1px solid rgba(245,158,11,0.25); }
.weather-status-normal  { background:rgba(34,197,94,0.12);  color:#22c55e; border:1px solid rgba(34,197,94,0.25); }
.weather-status-cold    { background:rgba(56,189,248,0.12); color:#38bdf8; border:1px solid rgba(56,189,248,0.25); }
.temp-bar { height:6px; border-radius:3px; background:#1e293b; overflow:hidden; margin-top:4px; }
.temp-bar-fill { height:100%; border-radius:3px; }
.empty-state { text-align:center; padding:60px 20px; color:#475569; }
.empty-icon  { font-size:56px; margin-bottom:16px; opacity:0.35; }
</style>

<div class="weather-wrapper">

{{-- PAGE HEADER --}}
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <div class="page-title">☁️ Weather Monitoring</div>
        <p class="text-secondary mb-0" style="font-size:13px;">Global Supply Chain Weather Intelligence Dashboard</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <span class="badge" style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);color:#38bdf8;font-size:11px;">
            🕐 {{ now()->format('d M Y H:i') }}
        </span>
        <span class="badge" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#86efac;font-size:11px;">
            🟢 Open-Meteo API
        </span>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Countries Monitored</div>
            <div class="kpi-value">{{ $totalMonitored }}</div>
            <div class="kpi-sub">Weather records</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-danger">
            <div class="kpi-label">Extreme Heat</div>
            <div class="kpi-value">{{ $extremeHeat }}</div>
            <div class="kpi-sub">Temp &gt; 35°C</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-warning">
            <div class="kpi-label">Storm Risk</div>
            <div class="kpi-value">{{ $stormRisk }}</div>
            <div class="kpi-sub">Active alerts</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Avg Temperature</div>
            <div class="kpi-value" style="font-size:26px;">{{ $avgTemperature ? round($avgTemperature, 1) . '°C' : '—' }}</div>
            <div class="kpi-sub">Global average</div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="section-card mb-4">
    <form method="GET" action="{{ route('weather.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Search Country</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Country name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Filter Region</label>
                <select name="region" class="form-select form-select-sm">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Temperature</label>
                <select name="temp_filter" class="form-select form-select-sm">
                    <option value="">All Temperatures</option>
                    <option value="hot"     {{ request('temp_filter') == 'hot'     ? 'selected' : '' }}>🔥 Hot (&gt;30°C)</option>
                    <option value="cold"    {{ request('temp_filter') == 'cold'    ? 'selected' : '' }}>❄️ Cold (&lt;10°C)</option>
                    <option value="extreme" {{ request('temp_filter') == 'extreme' ? 'selected' : '' }}>⚠️ Extreme</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Weather Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="Normal"     {{ request('status') == 'Normal'     ? 'selected' : '' }}>Normal</option>
                    <option value="Storm Risk" {{ request('status') == 'Storm Risk' ? 'selected' : '' }}>Storm Risk</option>
                    <option value="Heavy Rain" {{ request('status') == 'Heavy Rain' ? 'selected' : '' }}>Heavy Rain</option>
                    <option value="Extreme"    {{ request('status') == 'Extreme'    ? 'selected' : '' }}>Extreme</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill" style="border-radius:8px;">
                    🔍 Filter
                </button>
                <a href="{{ route('weather.index') }}" class="btn btn-sm flex-fill" style="background:#1e293b;color:#94a3b8;border:1px solid #334155;border-radius:8px;">
                    ✕ Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- WEATHER TABLE --}}
<div class="section-card">
    <div class="section-title">
        🌡️ Global Weather Data
        <span class="badge-pill">{{ $weatherData->total() }} records</span>
    </div>

    @if($weatherData->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🌤️</div>
            <h5 style="color:#475569;">No Weather Data Available</h5>
            <p class="text-secondary">Weather data will appear here after syncing with the API.</p>
            <p class="text-secondary" style="font-size:12px;">Open any country's Intelligence Center and click "Sync Weather" to start.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg:#0f172a;--bs-table-border-color:#1e293b;--bs-table-hover-bg:#1e293b;">
                <thead>
                    <tr style="border-bottom:1px solid #1e293b;">
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">#</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Country</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Region</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Temperature</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Rainfall</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Wind Speed</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Status</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Last Update</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weatherData as $wd)
                    @php
                        $temp = $wd->temperature;
                        $tempColor  = $temp > 35 ? '#ef4444' : ($temp > 25 ? '#f59e0b' : ($temp < 5 ? '#38bdf8' : '#22c55e'));
                        $tempBg     = $temp > 35 ? 'rgba(239,68,68,0.12)' : ($temp > 25 ? 'rgba(245,158,11,0.12)' : ($temp < 5 ? 'rgba(56,189,248,0.12)' : 'rgba(34,197,94,0.12)'));
                        $tempLabel  = $temp > 35 ? 'Extreme Heat' : ($temp > 25 ? 'Warm' : ($temp < 5 ? 'Cold' : 'Normal'));
                        $tempPct    = min(100, max(0, (($temp + 20) / 80) * 100));
                    @endphp
                    <tr>
                        <td style="color:#64748b;font-size:12px;padding:10px 12px;">{{ $weatherData->firstItem() + $loop->index }}</td>
                        <td style="padding:10px 12px;">
                            <div class="d-flex align-items-center gap-2">
                                @if($wd->country?->flag)
                                    <img src="{{ $wd->country->flag }}" width="28" height="20" style="border-radius:3px;object-fit:cover;" onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:#f1f5f9;">{{ $wd->country?->name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:10px 12px;"><span style="font-size:11px;color:#64748b;">{{ $wd->country?->region ?? '—' }}</span></td>
                        <td style="padding:10px 12px;">
                            <span style="font-size:15px;font-weight:700;color:{{ $tempColor }};">{{ $temp !== null ? $temp . '°C' : '—' }}</span>
                            @if($temp !== null)
                                <div class="temp-bar" style="width:80px;">
                                    <div class="temp-bar-fill" style="width:{{ $tempPct }}%;background:{{ $tempColor }};"></div>
                                </div>
                            @endif
                        </td>
                        <td style="padding:10px 12px;color:#94a3b8;font-size:12px;">{{ $wd->rainfall !== null ? $wd->rainfall . ' mm' : '—' }}</td>
                        <td style="padding:10px 12px;color:#94a3b8;font-size:12px;">{{ $wd->wind_speed !== null ? $wd->wind_speed . ' km/h' : '—' }}</td>
                        <td style="padding:10px 12px;">
                            @php
                                $statusBg = match($wd->weather_status) {
                                    'Storm Risk'  => 'rgba(239,68,68,0.12)',
                                    'Heavy Rain'  => 'rgba(56,189,248,0.12)',
                                    'Extreme'     => 'rgba(245,158,11,0.12)',
                                    default       => 'rgba(34,197,94,0.12)',
                                };
                                $statusColor = match($wd->weather_status) {
                                    'Storm Risk'  => '#ef4444',
                                    'Heavy Rain'  => '#38bdf8',
                                    'Extreme'     => '#f59e0b',
                                    default       => '#22c55e',
                                };
                                $statusIcon = match($wd->weather_status) {
                                    'Storm Risk'  => '⛈️',
                                    'Heavy Rain'  => '🌧️',
                                    'Extreme'     => '🌡️',
                                    default       => '☀️',
                                };
                            @endphp
                            <span style="background:{{ $statusBg }};color:{{ $statusColor }};border:1px solid {{ $statusColor }}33;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">
                                {{ $statusIcon }} {{ $wd->weather_status ?? 'Normal' }}
                            </span>
                        </td>
                        <td style="padding:10px 12px;color:#64748b;font-size:11px;">{{ $wd->updated_at?->diffForHumans() ?? '—' }}</td>
                        <td style="padding:10px 12px;">
                            @if($wd->country)
                                <a href="{{ route('countries.show', $wd->country->id) }}"
                                   class="btn btn-sm"
                                   style="background:rgba(37,99,235,0.2);color:#38bdf8;border:1px solid rgba(56,189,248,0.25);border-radius:8px;font-size:11px;padding:4px 10px;">
                                    🌍 Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($weatherData->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 px-1">
                <div style="font-size:12px;color:#64748b;">
                    Showing {{ $weatherData->firstItem() }}–{{ $weatherData->lastItem() }} of {{ $weatherData->total() }} records
                </div>
                <div>
                    {{ $weatherData->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @endif
</div>

{{-- TOP EXTREME WEATHER --}}
@php
    $extremeCountries = \App\Models\WeatherData::with('country')
        ->where('temperature', '>', 30)
        ->orWhere('weather_status', 'Storm Risk')
        ->orderByDesc('temperature')
        ->limit(5)
        ->get();
@endphp

@if($extremeCountries->isNotEmpty())
<div class="section-card">
    <div class="section-title">
        ⚠️ Extreme Weather Alert
        <span class="badge-pill">Top {{ $extremeCountries->count() }}</span>
    </div>
    <div class="row g-3">
        @foreach($extremeCountries as $ec)
        @php
            $ecColor = $ec->temperature > 35 ? '#ef4444' : '#f59e0b';
        @endphp
        <div class="col-md-4 col-lg-3 col-xl-2">
            <div style="background:linear-gradient(135deg,rgba(239,68,68,0.08),rgba(245,158,11,0.06));border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:14px;cursor:pointer;transition:all 0.2s;"
                 onclick="window.location='{{ $ec->country ? route('countries.show', $ec->country->id) : '#' }}'">
                <div class="d-flex align-items-center gap-2 mb-2">
                    @if($ec->country?->flag)
                        <img src="{{ $ec->country->flag }}" width="22" height="15" style="border-radius:2px;object-fit:cover;" onerror="this.style.display='none'">
                    @endif
                    <span style="font-size:12px;font-weight:600;color:#f1f5f9;">{{ $ec->country?->name ?? '—' }}</span>
                </div>
                <div style="font-size:24px;font-weight:800;color:{{ $ecColor }};">{{ $ec->temperature !== null ? $ec->temperature . '°' : '—' }}</div>
                <div style="font-size:10px;color:#64748b;margin-top:2px;">{{ $ec->weather_status ?? 'Normal' }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

</div>

</x-dashboard-layout>
