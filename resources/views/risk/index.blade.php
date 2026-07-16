<x-dashboard-layout>

@section('title', 'Risk Analytics')

{{-- ============================================================
     RISK ANALYTICS — GERIP ENTERPRISE DASHBOARD
     ============================================================ --}}

<style>

/* ===== PAGE FADE IN ===== */
.analytics-wrapper { animation: fadeInUp 0.5s ease both; }

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ===== SECTION CARDS ===== */
.section-card {
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 24px;
    transition: box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}
.section-card:hover { box-shadow: 0 0 40px rgba(37,99,235,0.12); }

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-title span.badge-pill {
    font-size: 10px;
    background: rgba(56,189,248,0.15);
    color: #38bdf8;
    border: 1px solid rgba(56,189,248,0.25);
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 600;
}

/* ===== KPI CARDS ===== */
.kpi-card {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    border: 1px solid #1e293b;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.kpi-card::after {
    content:'';
    position:absolute;
    top:-30px; right:-30px;
    width:100px; height:100px;
    border-radius:50%;
    opacity:0.07;
}
.kpi-card.kpi-danger  { border-color: rgba(239,68,68,0.35); }
.kpi-card.kpi-warning { border-color: rgba(245,158,11,0.35); }
.kpi-card.kpi-success { border-color: rgba(34,197,94,0.35); }
.kpi-card.kpi-info    { border-color: rgba(56,189,248,0.35); }
.kpi-card.kpi-danger::after  { background: #ef4444; }
.kpi-card.kpi-warning::after { background: #f59e0b; }
.kpi-card.kpi-success::after { background: #22c55e; }
.kpi-card.kpi-info::after    { background: #38bdf8; }
.kpi-card:hover { transform: translateY(-5px); }
.kpi-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; }
.kpi-value { font-size: 32px; font-weight: 800; line-height: 1; }
.kpi-sub   { font-size: 11px; color: #64748b; margin-top: 6px; }
.kpi-danger .kpi-value  { color: #ef4444; }
.kpi-warning .kpi-value { color: #f59e0b; }
.kpi-success .kpi-value { color: #22c55e; }
.kpi-info .kpi-value    { color: #38bdf8; }

/* ===== PAGE HEADER ===== */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}
.page-title {
    font-size: 24px;
    font-weight: 800;
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

/* ===== FILTER BAR ===== */
.filter-bar {
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 24px;
}

/* ===== TOP COUNTRIES ===== */
.top-country-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 12px;
    transition: background 0.2s;
    cursor: pointer;
    border: 1px solid transparent;
    margin-bottom: 6px;
}
.top-country-item:hover { background: #1e293b; border-color: #334155; }
.rank-badge {
    width: 26px; height: 26px;
    border-radius: 50%;
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.rank-1 { background: linear-gradient(135deg,#f59e0b,#d97706); color:#000; }
.rank-2 { background: linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
.rank-3 { background: linear-gradient(135deg,#cd7f32,#a0522d); color:#fff; }
.rank-n { background: #1e293b; color:#64748b; }
.trend-up   { color:#ef4444; font-weight:700; font-size:14px; }
.trend-down { color:#22c55e; font-weight:700; font-size:14px; }
.trend-flat { color:#64748b; font-weight:700; font-size:14px; }

/* ===== ALERT PANEL ===== */
.alert-panel { max-height: 400px; overflow-y: auto; }
.alert-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #1e293b;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.alert-item:hover { background: #1e293b; border-color: #334155; }
.alert-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
    animation: alertPulse 2s ease-in-out infinite;
}
.alert-dot.danger  { background:#ef4444; box-shadow:0 0 8px rgba(239,68,68,0.6); }
.alert-dot.warning { background:#f59e0b; box-shadow:0 0 8px rgba(245,158,11,0.5); }
@keyframes alertPulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:0.5; transform:scale(1.4); }
}

/* ===== AI CARDS ===== */
.ai-card {
    background: linear-gradient(135deg,rgba(37,99,235,0.12),rgba(6,182,212,0.08));
    border: 1px solid rgba(56,189,248,0.2);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ai-stat { font-size: 26px; font-weight: 800; }

/* ===== SKELETON LOADER ===== */
.skeleton {
    background: linear-gradient(90deg,#1e293b 25%,#334155 50%,#1e293b 75%);
    background-size: 200% 100%;
    animation: skeletonAnim 1.5s ease-in-out infinite;
    border-radius: 8px;
}
@keyframes skeletonAnim {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ===== LEADERBOARD TABLE ===== */
.leaderboard-table th {
    font-size: 10px;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.8px;
    padding: 10px 12px;
    border-bottom: 1px solid #1e293b;
    background: #020617;
}
.leaderboard-table td {
    padding: 10px 12px;
    border-bottom: 1px solid rgba(30,41,59,0.5);
    font-size: 13px;
    vertical-align: middle;
}
.leaderboard-table tr:hover td { background: rgba(30,41,59,0.5); }

/* ===== RISK PROGRESS BAR ===== */
.risk-progress { height:6px; border-radius:3px; overflow:hidden; background:#1e293b; }
.risk-progress-bar { height:100%; border-radius:3px; transition:width 1s ease; }

/* ===== API STATUS DOT ===== */
.api-status-dot {
    width:8px; height:8px;
    border-radius:50%;
    display:inline-block;
    margin-right:6px;
}
.api-status-dot.online  { background:#22c55e; box-shadow:0 0 6px #22c55e; }
.api-status-dot.offline { background:#ef4444; }

/* ===== PERIOD BUTTONS ===== */
.period-btn {
    background: #1e293b;
    border: 1px solid #334155;
    color: #94a3b8;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.period-btn:hover,
.period-btn.active {
    background: linear-gradient(135deg,#2563eb,#06b6d4);
    border-color: transparent;
    color: #fff;
}

/* ===== GLOBAL PROGRESS BAR ===== */
#globalLoader {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 4px;
    background: linear-gradient(90deg,#2563eb,#06b6d4,#22c55e,#2563eb);
    background-size: 200% 100%;
    animation: progressAnim 1.5s linear infinite;
    z-index: 9999;
    display: none;
}
@keyframes progressAnim {
    0%   { background-position: 0% 0; }
    100% { background-position: 200% 0; }
}

/* ===== EMPTY STATE ===== */
.empty-analytics { text-align:center; padding:60px 20px; }
.empty-analytics .icon { font-size:56px; opacity:0.3; margin-bottom:16px; }

/* ===== GLOW ===== */
.glow-blue { box-shadow: 0 0 30px rgba(37,99,235,0.15); }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .kpi-value { font-size: 24px; }
}

</style>

{{-- TOP LOADING BAR --}}
<div id="globalLoader"></div>

<div class="analytics-wrapper">

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="page-header">
    <div>
        <div class="page-title">🚨 Global Risk Analytics</div>
        <p class="text-secondary mb-0" style="font-size:13px;">
            Real-time Supply Chain Risk Intelligence Dashboard
            <span class="badge ms-2" style="background:rgba(34,197,94,0.15);color:#86efac;border:1px solid rgba(34,197,94,0.25);font-size:10px;">
                🔄 Auto-refresh: ON
            </span>
        </p>
    </div>
    <div class="header-actions">
        <div class="d-flex gap-2">
            <button class="period-btn active" data-period="7">7D</button>
            <button class="period-btn" data-period="30">30D</button>
            <button class="period-btn" data-period="180">6M</button>
        </div>
        <button id="refreshAllBtn" class="btn btn-sm" style="background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:13px;">
            <span id="refreshIcon">⚡</span> Refresh Analytics
        </button>
        <span class="badge" style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);color:#38bdf8;font-size:11px;" id="lastUpdateBadge">
            🕐 {{ now()->format('d M Y H:i') }}
        </span>
    </div>
</div>

{{-- ============================================================
     ADVANCED FILTER BAR
     ============================================================ --}}
<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3 col-sm-6">
            <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Search Country</label>
            <input type="text" id="filterCountry" class="form-control form-control-sm" placeholder="Country name...">
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Region</label>
            <select id="filterRegion" class="form-select form-select-sm">
                <option value="">All Regions</option>
                @foreach($regions as $region)
                    <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Risk Level</label>
            <select id="filterRisk" class="form-select form-select-sm">
                <option value="">All Levels</option>
                <option value="High Risk">🔴 High Risk</option>
                <option value="Medium Risk">🟡 Medium Risk</option>
                <option value="Low Risk">🟢 Low Risk</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Sort By</label>
            <select id="filterSort" class="form-select form-select-sm">
                <option value="score_desc">Highest Risk</option>
                <option value="score_asc">Lowest Risk</option>
                <option value="name_asc">A → Z</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-12">
            <button id="applyFilter" class="btn btn-sm btn-primary w-100" style="border-radius:8px;">
                🔍 Apply Filter
            </button>
        </div>
    </div>
</div>

{{-- ============================================================
     KPI SUMMARY CARDS
     ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Countries Monitored</div>
            <div class="kpi-value" id="kpiTotal">{{ $totalMonitored }}</div>
            <div class="kpi-sub">In database</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-danger">
            <div class="kpi-label">High Risk</div>
            <div class="kpi-value" id="kpiHigh">{{ $highRisk }}</div>
            <div class="kpi-sub">🔴 Countries</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-warning">
            <div class="kpi-label">Medium Risk</div>
            <div class="kpi-value" id="kpiMedium">{{ $mediumRisk }}</div>
            <div class="kpi-sub">🟡 Countries</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-success">
            <div class="kpi-label">Low Risk</div>
            <div class="kpi-value" id="kpiLow">{{ $lowRisk }}</div>
            <div class="kpi-sub">🟢 Countries</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Avg Global Risk</div>
            <div class="kpi-value" id="kpiAvg">—</div>
            <div class="kpi-sub">Score / 100</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-danger">
            <div class="kpi-label">Highest Risk</div>
            <div class="kpi-value" id="kpiHighest">—</div>
            <div class="kpi-sub">Max score</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-success">
            <div class="kpi-label">Lowest Risk</div>
            <div class="kpi-value" id="kpiLowest">—</div>
            <div class="kpi-sub">Min score</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-warning">
            <div class="kpi-label">Avg Inflation</div>
            <div class="kpi-value" id="kpiInflation">—</div>
            <div class="kpi-sub">% Average</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Avg Temperature</div>
            <div class="kpi-value" id="kpiTemp">—</div>
            <div class="kpi-sub">°C Average</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-warning">
            <div class="kpi-label">Avg Currency Rate</div>
            <div class="kpi-value" id="kpiRate" style="font-size:20px;">—</div>
            <div class="kpi-sub">vs USD</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-4">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Realtime API Status</div>
            <div class="d-flex flex-wrap gap-3 mt-2">
                <div><span class="api-status-dot" id="dot-weather"></span><span style="font-size:12px;color:#94a3b8;">Weather</span></div>
                <div><span class="api-status-dot" id="dot-economic"></span><span style="font-size:12px;color:#94a3b8;">Economic</span></div>
                <div><span class="api-status-dot" id="dot-currency"></span><span style="font-size:12px;color:#94a3b8;">Currency</span></div>
                <div><span class="api-status-dot" id="dot-news"></span><span style="font-size:12px;color:#94a3b8;">News</span></div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     GLOBAL RISK HEATMAP
     ============================================================ --}}
<div class="section-card glow-blue">
    <div class="section-title">
        🌍 Global Risk Heatmap
        <span class="badge-pill">Leaflet · Live</span>
    </div>
    <div id="riskMap" style="height:520px;border-radius:14px;overflow:hidden;"></div>
</div>

{{-- ============================================================
     CHARTS ROW — Trend + Distribution
     ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="section-card" style="height:420px;">
            <div class="section-title">
                📈 Global Risk Trend
                <span class="badge-pill">Top 20 · Bar Chart</span>
            </div>
            <div style="height:340px;position:relative;">
                <div id="trendLoading" class="skeleton" style="height:320px;border-radius:10px;"></div>
                <canvas id="riskTrendChart" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="section-card" style="height:420px;">
            <div class="section-title">
                🍩 Risk Distribution
                <span class="badge-pill">Doughnut</span>
            </div>
            <div style="height:340px;position:relative;">
                <div id="distLoading" class="skeleton" style="height:320px;border-radius:10px;"></div>
                <canvas id="riskDistChart" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     CHARTS ROW — Regional + Radar
     ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-xl-7">
        <div class="section-card" style="height:360px;">
            <div class="section-title">
                🗺️ Regional Risk Comparison
                <span class="badge-pill">Avg Risk / Region</span>
            </div>
            <div style="height:280px;position:relative;">
                <div id="regionalLoading" class="skeleton" style="height:260px;border-radius:10px;"></div>
                <canvas id="riskRegionalChart" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="section-card" style="height:360px;">
            <div class="section-title">
                🎯 Risk Factor Analysis
                <span class="badge-pill">Radar Chart</span>
            </div>
            <div style="height:280px;position:relative;">
                <div id="radarLoading" class="skeleton" style="height:260px;border-radius:10px;"></div>
                <canvas id="riskRadarChart" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     TOP COUNTRIES + ALERTS + AI RECOMMENDATION
     ============================================================ --}}
<div class="row g-3 mb-4">

    {{-- TOP 10 --}}
    <div class="col-xl-5">
        <div class="section-card" style="min-height:480px;">
            <div class="section-title">
                🏆 Top Risk Countries
                <span class="badge-pill">Top 10</span>
            </div>
            <div id="topCountriesPanel">
                @forelse($topCountries as $idx => $tc)
                    @php
                        $tcColor = $tc['risk_level'] === 'High Risk' ? '#ef4444' : ($tc['risk_level'] === 'Medium Risk' ? '#f59e0b' : '#22c55e');
                        $rankClass = $idx === 0 ? 'rank-1' : ($idx === 1 ? 'rank-2' : ($idx === 2 ? 'rank-3' : 'rank-n'));
                        $trendClass = $tc['trend'] === '↑' ? 'trend-up' : ($tc['trend'] === '↓' ? 'trend-down' : 'trend-flat');
                    @endphp
                    <div class="top-country-item"
                         onclick="window.location='{{ route('countries.show', $tc['id']) }}'">
                        <div class="rank-badge {{ $rankClass }}">{{ $idx + 1 }}</div>
                        @if($tc['flag'])
                            <img src="{{ $tc['flag'] }}" width="28" height="20"
                                 style="border-radius:3px;object-fit:cover;flex-shrink:0;"
                                 onerror="this.style.display='none'">
                        @endif
                        <div class="flex-grow-1 overflow-hidden">
                            <div style="font-size:13px;font-weight:600;color:#f1f5f9;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $tc['name'] }}
                            </div>
                            <div style="font-size:11px;color:#64748b;">{{ $tc['region'] ?? '—' }}</div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div style="font-size:16px;font-weight:800;color:{{ $tcColor }};">
                                {{ $tc['total_score'] }}
                            </div>
                            <div class="{{ $trendClass }}">{{ $tc['trend'] }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-analytics">
                        <div class="icon">📊</div>
                        <p class="text-secondary">No analytics data yet</p>
                        <button onclick="refreshAll()" class="btn btn-sm btn-primary mt-2">⚡ Refresh Analytics</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- LIVE ALERT PANEL --}}
    <div class="col-xl-4">
        <div class="section-card" style="min-height:480px;">
            <div class="section-title">
                🔴 Live Alert Panel
                <span class="badge-pill">Negative Signals</span>
            </div>
            <div id="alertPanel" class="alert-panel">
                <div class="skeleton" style="height:60px;border-radius:10px;margin-bottom:8px;"></div>
                <div class="skeleton" style="height:60px;border-radius:10px;margin-bottom:8px;"></div>
                <div class="skeleton" style="height:60px;border-radius:10px;margin-bottom:8px;"></div>
                <div class="skeleton" style="height:60px;border-radius:10px;margin-bottom:8px;"></div>
                <div class="skeleton" style="height:60px;border-radius:10px;"></div>
            </div>
        </div>
    </div>

    {{-- AI RECOMMENDATION --}}
    <div class="col-xl-3">
        <div class="section-card" style="min-height:480px;">
            <div class="section-title">
                🤖 AI Recommendation
                <span class="badge-pill">Summary</span>
            </div>
            <div id="aiRecPanel">
                <div class="skeleton" style="height:70px;border-radius:10px;margin-bottom:10px;"></div>
                <div class="skeleton" style="height:70px;border-radius:10px;margin-bottom:10px;"></div>
                <div class="skeleton" style="height:70px;border-radius:10px;margin-bottom:10px;"></div>
                <div class="skeleton" style="height:70px;border-radius:10px;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     GLOBAL LEADERBOARD
     ============================================================ --}}
<div class="section-card">
    <div class="section-title">
        📋 Global Leaderboard
        <span class="badge-pill">All Countries</span>
    </div>
    <div class="mb-3">
        <input type="text" id="leaderboardSearch" class="form-control form-control-sm"
               placeholder="🔍 Search in leaderboard..."
               style="max-width:300px;">
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle leaderboard-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>Risk Score</th>
                    <th>Status</th>
                    <th>Temp</th>
                    <th>Currency</th>
                    <th>GDP</th>
                    <th>Recommendation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="leaderboardBody">
                @for($i = 0; $i < 8; $i++)
                    <tr>
                        <td><div class="skeleton" style="width:24px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:120px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:80px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:80px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:80px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:50px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:50px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:80px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:120px;height:14px;"></div></td>
                        <td><div class="skeleton" style="width:60px;height:14px;"></div></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="leaderboardEmpty" class="empty-analytics" style="display:none;">
        <div class="icon">📭</div>
        <p class="text-secondary">No data available</p>
        <button onclick="refreshAll()" class="btn btn-sm btn-primary">⚡ Refresh Analytics</button>
    </div>
</div>

</div>{{-- end .analytics-wrapper --}}

{{-- ============================================================
     MAP DATA — Pre-computed in Controller, safe @json usage
     ============================================================ --}}
<script>
    var riskMapData    = @json($riskMapData);
    var AJAX_URL       = '{{ route("risk.analytics.api") }}';
    var REFRESH_URL    = '{{ route("risk.analytics.refresh") }}';
    var CSRF_TOKEN     = '{{ csrf_token() }}';
</script>

{{-- ============================================================
     MAIN JAVASCRIPT
     ============================================================ --}}
<script>

// ------------------------------------------------
// STATE
// ------------------------------------------------
var currentPeriod     = 7;
var riskTrendChart    = null;
var riskDistChart     = null;
var riskRegionalChart = null;
var riskRadarChart    = null;
var leafletMap        = null;
var leafletMarkers    = [];
var allLeaderboardData = [];
var autoRefreshTimer  = null;

// ------------------------------------------------
// INIT
// ------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {

    initMap();
    loadMapMarkersFromServer();
    loadAnalyticsData();
    startAutoRefresh();

    // Period buttons
    var periodBtns = document.querySelectorAll('.period-btn');
    for (var i = 0; i < periodBtns.length; i++) {
        periodBtns[i].addEventListener('click', function () {
            for (var j = 0; j < periodBtns.length; j++) {
                periodBtns[j].classList.remove('active');
            }
            this.classList.add('active');
            currentPeriod = parseInt(this.getAttribute('data-period'));
            loadAnalyticsData();
        });
    }

    // Refresh button
    document.getElementById('refreshAllBtn').addEventListener('click', refreshAll);

    // Filter button
    document.getElementById('applyFilter').addEventListener('click', applyLeaderboardFilter);

    // Leaderboard search
    document.getElementById('leaderboardSearch').addEventListener('input', applyLeaderboardFilter);

    // Filter selects — apply on change
    var filterSelects = ['filterRegion', 'filterRisk', 'filterSort'];
    for (var k = 0; k < filterSelects.length; k++) {
        var el = document.getElementById(filterSelects[k]);
        if (el) {
            el.addEventListener('change', applyLeaderboardFilter);
        }
    }

});

// ------------------------------------------------
// LEAFLET MAP INIT
// ------------------------------------------------
function initMap() {
    leafletMap = L.map('riskMap', {
        center: [20, 10],
        zoom: 2,
        zoomControl: true
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        maxZoom: 18
    }).addTo(leafletMap);
}

// ------------------------------------------------
// LOAD MAP MARKERS (server-side data)
// ------------------------------------------------
function loadMapMarkersFromServer() {
    if (!riskMapData || !riskMapData.length) return;

    for (var i = 0; i < riskMapData.length; i++) {
        addMapMarker(riskMapData[i]);
    }
}

function addMapMarker(r) {
    if (!r.lat || !r.lng) return;

    var color = '#22c55e';
    if (r.risk_level === 'High Risk')   color = '#ef4444';
    if (r.risk_level === 'Medium Risk') color = '#f59e0b';

    var radius = Math.max(6, r.total_score / 10);

    var gdpStr  = r.gdp ? '$' + (r.gdp / 1e9).toFixed(1) + 'B' : '—';
    var tempStr = r.temperature != null ? r.temperature + '°C' : '—';
    var currStr = r.currency || '—';
    var recStr  = r.recommendation || '—';
    var flagImg = r.flag ? '<img src="' + r.flag + '" width="24" height="17" style="border-radius:3px;object-fit:cover;vertical-align:middle;margin-right:6px;">' : '';

    var popupHtml = '<div style="min-width:230px;font-family:Inter,sans-serif;">'
        + '<div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">'
        + flagImg
        + '<strong style="font-size:15px;">' + (r.name || 'Unknown') + '</strong>'
        + '</div>'
        + '<table style="font-size:12px;width:100%;border-collapse:collapse;margin-bottom:10px;">'
        + '<tr><td style="color:#64748b;padding:2px 0;">Risk Score</td><td><strong style="color:' + color + ';font-size:16px;">' + r.total_score + '</strong></td></tr>'
        + '<tr><td style="color:#64748b;padding:2px 0;">Level</td><td><strong style="color:' + color + ';">' + r.risk_level + '</strong></td></tr>'
        + '<tr><td style="color:#64748b;padding:2px 0;">Temperature</td><td>' + tempStr + '</td></tr>'
        + '<tr><td style="color:#64748b;padding:2px 0;">Currency</td><td>' + currStr + '</td></tr>'
        + '<tr><td style="color:#64748b;padding:2px 0;">GDP</td><td>' + gdpStr + '</td></tr>'
        + '<tr><td style="color:#64748b;padding:2px 0;vertical-align:top;">Recommendation</td><td>' + recStr + '</td></tr>'
        + '</table>'
        + '<a href="/countries/' + r.country_id + '" style="display:block;text-align:center;background:#2563eb;color:#fff;padding:8px;border-radius:8px;text-decoration:none;font-size:12px;font-weight:600;">'
        + '🔍 Open Intelligence Center'
        + '</a>'
        + '</div>';

    var marker = L.circleMarker([r.lat, r.lng], {
        radius: radius,
        color: color,
        fillColor: color,
        fillOpacity: 0.8,
        weight: 2
    });

    marker.bindPopup(popupHtml, { maxWidth: 280 });
    marker.addTo(leafletMap);
    leafletMarkers.push(marker);
}

// ------------------------------------------------
// LOAD ANALYTICS DATA (AJAX)
// ------------------------------------------------
function loadAnalyticsData() {
    showLoader(true);

    fetch(AJAX_URL + '?period=' + currentPeriod, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        updateKPICards(data.summary, data.apiStatus);
        updateTrendChart(data.trendChart);
        updateDistributionChart(data.distributionChart);
        updateRegionalChart(data.regionalChart);
        updateRadarChart(data.radarChart);
        updateTopCountries(data.topCountries);
        updateAlertPanel(data.alerts);
        updateAIRecommendation(data.aiRecommendation);
        updateLeaderboard(data.leaderboard);
        allLeaderboardData = data.leaderboard;

        var now = new Date();
        document.getElementById('lastUpdateBadge').textContent = '🕐 ' + now.toLocaleTimeString();
        showLoader(false);
    })
    .catch(function (err) {
        console.error('AJAX Error:', err);
        showLoader(false);
    });
}

// ------------------------------------------------
// UPDATE KPI CARDS
// ------------------------------------------------
function updateKPICards(summary, apiStatus) {
    animateCounter('kpiTotal',  parseInt(summary.total));
    animateCounter('kpiHigh',   parseInt(summary.high));
    animateCounter('kpiMedium', parseInt(summary.medium));
    animateCounter('kpiLow',    parseInt(summary.low));

    document.getElementById('kpiAvg').textContent      = summary.avg;
    document.getElementById('kpiHighest').textContent  = summary.highest;
    document.getElementById('kpiLowest').textContent   = summary.lowest;
    document.getElementById('kpiInflation').textContent = summary.avgInflation + '%';
    document.getElementById('kpiTemp').textContent     = summary.avgTemp + '°';
    document.getElementById('kpiRate').textContent     = parseFloat(summary.avgRate).toLocaleString();

    if (apiStatus) {
        var keys = ['weather', 'economic', 'currency', 'news'];
        for (var i = 0; i < keys.length; i++) {
            var dot = document.getElementById('dot-' + keys[i]);
            if (dot) {
                dot.className = 'api-status-dot ' + (apiStatus[keys[i]] ? 'online' : 'offline');
            }
        }
    }
}

// ------------------------------------------------
// COUNTER ANIMATION
// ------------------------------------------------
function animateCounter(id, target) {
    var el = document.getElementById(id);
    if (!el) return;
    var start    = parseInt(el.textContent) || 0;
    var duration = 800;
    var startTime = null;

    function step(now) {
        if (!startTime) startTime = now;
        var progress = Math.min((now - startTime) / duration, 1);
        var eased    = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(start + (target - start) * eased);
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

// ------------------------------------------------
// TREND BAR CHART
// ------------------------------------------------
function updateTrendChart(data) {
    var loading = document.getElementById('trendLoading');
    var canvas  = document.getElementById('riskTrendChart');

    loading.style.display = 'none';
    canvas.style.display  = 'block';

    if (riskTrendChart) riskTrendChart.destroy();

    riskTrendChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Risk Score',
                data: data.data,
                backgroundColor: data.colors,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1000, easing: 'easeOutQuart' },
            onClick: function (evt, elements) {
                if (elements.length > 0) {
                    var idx    = elements[0].index;
                    var detail = data.details[idx];
                    if (detail && detail.country_id) {
                        window.location.href = '/countries/' + detail.country_id;
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function (ctx) {
                            var d = data.details[ctx[0].dataIndex];
                            return d ? d.country : ctx[0].label;
                        },
                        label: function (ctx) {
                            var d = data.details[ctx.dataIndex];
                            if (!d) return 'Score: ' + ctx.parsed.y;
                            var lines = [
                                'Risk Score: ' + d.score,
                                'Level: ' + d.level
                            ];
                            if (d.temperature != null) lines.push('Temperature: ' + d.temperature + '\u00b0C');
                            if (d.currency)  lines.push('Currency: ' + d.currency);
                            if (d.gdp)       lines.push('GDP: $' + (d.gdp / 1e9).toFixed(1) + 'B');
                            lines.push('Updated: ' + d.updated_at);
                            lines.push('\ud83d\udd17 Click to open Intelligence');
                            return lines;
                        }
                    },
                    backgroundColor: '#0f172a',
                    borderColor: '#1e293b',
                    borderWidth: 1,
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8',
                    padding: 12,
                    cornerRadius: 10
                }
            },
            scales: {
                x: {
                    ticks: { color: '#64748b', font: { size: 10 }, maxRotation: 35 },
                    grid:  { color: 'rgba(30,41,59,0.5)' }
                },
                y: {
                    ticks: { color: '#64748b' },
                    grid:  { color: 'rgba(30,41,59,0.5)' },
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// ------------------------------------------------
// DOUGHNUT DISTRIBUTION CHART
// ------------------------------------------------
function updateDistributionChart(data) {
    var loading = document.getElementById('distLoading');
    var canvas  = document.getElementById('riskDistChart');

    loading.style.display = 'none';
    canvas.style.display  = 'block';

    var total = (data.high + data.medium + data.low) || 1;
    function pct(v) { return ((v / total) * 100).toFixed(1) + '%'; }

    if (riskDistChart) riskDistChart.destroy();

    riskDistChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: [
                'High Risk (' + pct(data.high) + ')',
                'Medium Risk (' + pct(data.medium) + ')',
                'Low Risk (' + pct(data.low) + ')'
            ],
            datasets: [{
                data: [data.high, data.medium, data.low],
                backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                borderColor: ['rgba(239,68,68,0.3)', 'rgba(245,158,11,0.3)', 'rgba(34,197,94,0.3)'],
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1000 },
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', font: { size: 11 }, padding: 16, boxWidth: 12 }
                },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.label + ': ' + ctx.parsed + ' countries';
                        }
                    },
                    backgroundColor: '#0f172a',
                    borderColor: '#1e293b',
                    borderWidth: 1,
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8'
                }
            }
        }
    });
}

// ------------------------------------------------
// REGIONAL HORIZONTAL BAR CHART
// ------------------------------------------------
function updateRegionalChart(data) {
    var loading = document.getElementById('regionalLoading');
    var canvas  = document.getElementById('riskRegionalChart');

    loading.style.display = 'none';
    canvas.style.display  = 'block';

    var colors = [];
    for (var i = 0; i < data.data.length; i++) {
        var val = data.data[i];
        if (val >= 65)      colors.push('#ef4444');
        else if (val >= 35) colors.push('#f59e0b');
        else                colors.push('#22c55e');
    }

    if (riskRegionalChart) riskRegionalChart.destroy();

    riskRegionalChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Average Risk Score',
                data: data.data,
                backgroundColor: colors,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900 },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var count = data.counts[ctx.dataIndex];
                            return [' Avg Risk: ' + ctx.parsed.x, ' Countries: ' + count];
                        }
                    },
                    backgroundColor: '#0f172a',
                    borderColor: '#1e293b',
                    borderWidth: 1,
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8'
                }
            },
            scales: {
                x: {
                    ticks: { color: '#64748b' },
                    grid:  { color: 'rgba(30,41,59,0.5)' },
                    beginAtZero: true,
                    max: 100
                },
                y: {
                    ticks: { color: '#94a3b8', font: { size: 12 } },
                    grid:  { display: false }
                }
            }
        }
    });
}

// ------------------------------------------------
// RADAR CHART
// ------------------------------------------------
function updateRadarChart(data) {
    var loading = document.getElementById('radarLoading');
    var canvas  = document.getElementById('riskRadarChart');

    loading.style.display = 'none';
    canvas.style.display  = 'block';

    if (riskRadarChart) riskRadarChart.destroy();

    riskRadarChart = new Chart(canvas, {
        type: 'radar',
        data: {
            labels: ['Weather Risk', 'Economic Risk', 'Currency Risk', 'News Risk'],
            datasets: [{
                label: 'Global Risk Factors',
                data: [data.weather, data.economy, data.currency, data.news],
                backgroundColor: 'rgba(37,99,235,0.15)',
                borderColor: '#2563eb',
                borderWidth: 2,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900 },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { color: '#64748b', backdropColor: 'transparent', stepSize: 20 },
                    grid:  { color: 'rgba(30,41,59,0.8)' },
                    angleLines: { color: 'rgba(30,41,59,0.8)' },
                    pointLabels: { color: '#94a3b8', font: { size: 11 } }
                }
            },
            plugins: {
                legend: { labels: { color: '#94a3b8', font: { size: 11 } } },
                tooltip: {
                    backgroundColor: '#0f172a',
                    borderColor: '#1e293b',
                    borderWidth: 1,
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8'
                }
            }
        }
    });
}

// ------------------------------------------------
// TOP COUNTRIES PANEL
// ------------------------------------------------
function updateTopCountries(countries) {
    var panel = document.getElementById('topCountriesPanel');

    if (!countries || countries.length === 0) {
        panel.innerHTML = '<div class="empty-analytics"><div class="icon">📊</div><p class="text-secondary">No data available</p><button onclick="refreshAll()" class="btn btn-sm btn-primary mt-2">⚡ Refresh</button></div>';
        return;
    }

    var html = '';
    for (var i = 0; i < countries.length; i++) {
        var tc = countries[i];
        var color = tc.risk_level === 'High Risk' ? '#ef4444' : (tc.risk_level === 'Medium Risk' ? '#f59e0b' : '#22c55e');
        var rankClass = i === 0 ? 'rank-1' : (i === 1 ? 'rank-2' : (i === 2 ? 'rank-3' : 'rank-n'));
        var trendClass = tc.trend === '↑' ? 'trend-up' : (tc.trend === '↓' ? 'trend-down' : 'trend-flat');
        var flagHtml = tc.flag ? '<img src="' + tc.flag + '" width="28" height="20" style="border-radius:3px;object-fit:cover;flex-shrink:0;" onerror="this.style.display=\'none\'">' : '';

        html += '<div class="top-country-item" onclick="window.location=\'/countries/' + tc.id + '\'">'
            + '<div class="rank-badge ' + rankClass + '">' + (i + 1) + '</div>'
            + flagHtml
            + '<div class="flex-grow-1 overflow-hidden">'
            + '<div style="font-size:13px;font-weight:600;color:#f1f5f9;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (tc.name || 'Unknown') + '</div>'
            + '<div style="font-size:11px;color:#64748b;">' + (tc.region || '—') + '</div>'
            + '</div>'
            + '<div class="text-end flex-shrink-0">'
            + '<div style="font-size:16px;font-weight:800;color:' + color + ';">' + tc.total_score + '</div>'
            + '<div class="' + trendClass + '">' + tc.trend + '</div>'
            + '</div>'
            + '</div>';
    }
    panel.innerHTML = html;
}

// ------------------------------------------------
// LIVE ALERT PANEL
// ------------------------------------------------
function updateAlertPanel(alerts) {
    var panel = document.getElementById('alertPanel');

    if (!alerts || alerts.length === 0) {
        panel.innerHTML = '<div class="empty-analytics" style="padding:40px 16px;"><div class="icon" style="font-size:40px;">📭</div><p class="text-secondary" style="font-size:13px;">No alerts at this time</p></div>';
        return;
    }

    var html = '';
    for (var i = 0; i < alerts.length; i++) {
        var a = alerts[i];
        var flagHtml = a.country_flag ? '<img src="' + a.country_flag + '" width="18" height="13" style="border-radius:2px;object-fit:cover;" onerror="this.style.display=\'none\'">' : '';
        var onclick  = a.country_id ? 'onclick="window.location=\'/countries/' + a.country_id + '\'"' : '';

        html += '<div class="alert-item" ' + onclick + '>'
            + '<div class="alert-dot danger"></div>'
            + '<div class="flex-grow-1 overflow-hidden">'
            + '<div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">'
            + flagHtml
            + '<span style="font-size:12px;font-weight:700;color:#f1f5f9;">' + (a.country_name || 'Global') + '</span>'
            + '<span style="font-size:10px;color:#64748b;margin-left:auto;">' + (a.time_ago || '') + '</span>'
            + '</div>'
            + '<div style="font-size:11px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (a.description || a.title || '') + '</div>'
            + '</div>'
            + '</div>';
    }
    panel.innerHTML = html;
}

// ------------------------------------------------
// AI RECOMMENDATION
// ------------------------------------------------
function updateAIRecommendation(ai) {
    var panel = document.getElementById('aiRecPanel');

    panel.innerHTML = ''
        + '<div class="ai-card mb-2">'
        + '<div>'
        + '<div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">✅ Countries Recommended</div>'
        + '<div style="font-size:11px;color:#86efac;margin-top:4px;">Suitable for trade</div>'
        + '</div>'
        + '<div class="ai-stat" style="color:#22c55e;">' + ai.recommended + '</div>'
        + '</div>'
        + '<div class="ai-card mb-2">'
        + '<div>'
        + '<div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">👀 Need Monitoring</div>'
        + '<div style="font-size:11px;color:#fcd34d;margin-top:4px;">Medium priority</div>'
        + '</div>'
        + '<div class="ai-stat" style="color:#f59e0b;">' + ai.monitoring + '</div>'
        + '</div>'
        + '<div class="ai-card mb-2">'
        + '<div>'
        + '<div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">🚫 Should Avoid</div>'
        + '<div style="font-size:11px;color:#fca5a5;margin-top:4px;">High risk exposure</div>'
        + '</div>'
        + '<div class="ai-stat" style="color:#ef4444;">' + ai.avoid + '</div>'
        + '</div>'
        + '<div class="ai-card">'
        + '<div>'
        + '<div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">🎯 Avg Confidence</div>'
        + '<div style="font-size:11px;color:#94a3b8;margin-top:4px;">Generated ' + (ai.generated_at || 'N/A') + '</div>'
        + '</div>'
        + '<div class="ai-stat" style="color:#38bdf8;">' + ai.avg_confidence + '%</div>'
        + '</div>';
}

// ------------------------------------------------
// LEADERBOARD TABLE
// ------------------------------------------------
function updateLeaderboard(data) {
    if (!data || data.length === 0) {
        document.getElementById('leaderboardBody').innerHTML = '';
        document.getElementById('leaderboardEmpty').style.display = 'block';
        return;
    }
    document.getElementById('leaderboardEmpty').style.display = 'none';
    renderLeaderboardRows(data);
}

function renderLeaderboardRows(data) {
    var tbody = document.getElementById('leaderboardBody');
    var html  = '';

    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        var levelColor = item.risk_level === 'High Risk' ? '#ef4444' : (item.risk_level === 'Medium Risk' ? '#f59e0b' : '#22c55e');
        var levelBg    = item.risk_level === 'High Risk' ? 'rgba(239,68,68,0.12)' : (item.risk_level === 'Medium Risk' ? 'rgba(245,158,11,0.12)' : 'rgba(34,197,94,0.12)');
        var levelIcon  = item.risk_level === 'High Risk' ? '🔴' : (item.risk_level === 'Medium Risk' ? '🟡' : '🟢');
        var gdpStr     = item.gdp ? '$' + (item.gdp / 1e9).toFixed(1) + 'B' : '—';
        var tempStr    = item.temperature != null ? item.temperature + '°C' : '—';
        var currStr    = item.currency || '—';
        var flagHtml   = item.flag
            ? '<img src="' + item.flag + '" width="28" height="20" style="border-radius:3px;object-fit:cover;" onerror="this.style.display=\'none\'">'
            : '<div style="width:28px;height:20px;background:#1e293b;border-radius:3px;flex-shrink:0;"></div>';

        html += '<tr>'
            + '<td><span style="font-size:11px;color:#64748b;font-weight:700;">' + item.rank + '</span></td>'
            + '<td><div style="display:flex;align-items:center;gap:8px;">'
            + flagHtml
            + '<div><div style="font-size:13px;font-weight:600;color:#f1f5f9;">' + (item.name || 'Unknown') + '</div></div>'
            + '</div></td>'
            + '<td><span style="font-size:11px;color:#64748b;">' + (item.region || '—') + '</span></td>'
            + '<td>'
            + '<div class="risk-progress mb-1"><div class="risk-progress-bar" style="width:' + item.total_score + '%;background:' + levelColor + ';"></div></div>'
            + '<span style="font-size:13px;font-weight:700;color:' + levelColor + ';">' + item.total_score + '</span>'
            + '</td>'
            + '<td><span style="background:' + levelBg + ';color:' + levelColor + ';border:1px solid ' + levelColor + '33;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">' + levelIcon + ' ' + item.risk_level + '</span></td>'
            + '<td style="color:#94a3b8;font-size:12px;">' + tempStr + '</td>'
            + '<td style="color:#94a3b8;font-size:12px;">' + currStr + '</td>'
            + '<td style="color:#94a3b8;font-size:12px;">' + gdpStr + '</td>'
            + '<td style="font-size:11px;color:#64748b;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (item.recommendation || '—') + '</td>'
            + '<td><a href="/countries/' + item.id + '" class="btn btn-sm" style="background:rgba(37,99,235,0.2);color:#38bdf8;border:1px solid rgba(56,189,248,0.25);border-radius:8px;font-size:11px;padding:4px 10px;">🔍 Detail</a></td>'
            + '</tr>';
    }

    tbody.innerHTML = html;
}

// ------------------------------------------------
// LEADERBOARD FILTER
// ------------------------------------------------
function applyLeaderboardFilter() {
    var data = allLeaderboardData.slice();

    var searchCountry = (document.getElementById('filterCountry').value || '').toLowerCase();
    var filterRegion  = document.getElementById('filterRegion').value;
    var filterRisk    = document.getElementById('filterRisk').value;
    var filterSort    = document.getElementById('filterSort').value;
    var lbSearch      = (document.getElementById('leaderboardSearch').value || '').toLowerCase();

    if (searchCountry) {
        data = data.filter(function (d) { return d.name.toLowerCase().indexOf(searchCountry) !== -1; });
    }
    if (filterRegion) {
        data = data.filter(function (d) { return d.region === filterRegion; });
    }
    if (filterRisk) {
        data = data.filter(function (d) { return d.risk_level === filterRisk; });
    }
    if (lbSearch) {
        data = data.filter(function (d) {
            return d.name.toLowerCase().indexOf(lbSearch) !== -1
                || (d.region || '').toLowerCase().indexOf(lbSearch) !== -1;
        });
    }

    if (filterSort === 'score_asc') {
        data.sort(function (a, b) { return a.total_score - b.total_score; });
    } else if (filterSort === 'name_asc') {
        data.sort(function (a, b) { return a.name.localeCompare(b.name); });
    } else {
        data.sort(function (a, b) { return b.total_score - a.total_score; });
    }

    for (var i = 0; i < data.length; i++) {
        data[i].rank = i + 1;
    }

    if (data.length === 0) {
        document.getElementById('leaderboardBody').innerHTML = '';
        document.getElementById('leaderboardEmpty').style.display = 'block';
    } else {
        document.getElementById('leaderboardEmpty').style.display = 'none';
        renderLeaderboardRows(data);
    }
}

// ------------------------------------------------
// REFRESH ALL
// ------------------------------------------------
function refreshAll() {
    var btn  = document.getElementById('refreshAllBtn');
    var icon = document.getElementById('refreshIcon');

    btn.disabled      = true;
    btn.style.opacity = '0.7';
    icon.textContent  = '⏳';
    showLoader(true);

    fetch(REFRESH_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        icon.textContent  = '✅';
        btn.disabled      = false;
        btn.style.opacity = '1';
        setTimeout(function () { icon.textContent = '⚡'; }, 2000);
        loadAnalyticsData();
    })
    .catch(function (err) {
        icon.textContent  = '❌';
        btn.disabled      = false;
        btn.style.opacity = '1';
        setTimeout(function () { icon.textContent = '⚡'; }, 2000);
        showLoader(false);
    });
}

// ------------------------------------------------
// AUTO REFRESH (5 minutes)
// ------------------------------------------------
function startAutoRefresh() {
    if (autoRefreshTimer) clearInterval(autoRefreshTimer);
    autoRefreshTimer = setInterval(function () {
        loadAnalyticsData();
    }, 5 * 60 * 1000);
}

// ------------------------------------------------
// LOADER
// ------------------------------------------------
function showLoader(show) {
    document.getElementById('globalLoader').style.display = show ? 'block' : 'none';
}

</script>

</x-dashboard-layout>