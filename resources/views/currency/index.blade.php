<x-dashboard-layout>

@section('title', 'Currency Intelligence')

<style>
.currency-wrapper { animation: fadeInUp 0.5s ease both; }
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
.kpi-card.kpi-info    { border-color:rgba(56,189,248,0.35); }
.kpi-card.kpi-info::after    { background:#38bdf8; }
.kpi-card.kpi-success { border-color:rgba(34,197,94,0.35); }
.kpi-card.kpi-success::after { background:#22c55e; }
.kpi-card.kpi-warning { border-color:rgba(245,158,11,0.35); }
.kpi-card.kpi-warning::after { background:#f59e0b; }
.kpi-card.kpi-danger  { border-color:rgba(239,68,68,0.35); }
.kpi-card.kpi-danger::after  { background:#ef4444; }
.kpi-card:hover { transform:translateY(-4px); }
.kpi-label { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:8px; }
.kpi-value { font-size:32px; font-weight:800; line-height:1; }
.kpi-sub   { font-size:11px; color:#64748b; margin-top:6px; }
.kpi-info .kpi-value    { color:#38bdf8; }
.kpi-success .kpi-value { color:#22c55e; }
.kpi-warning .kpi-value { color:#f59e0b; }
.kpi-danger .kpi-value  { color:#ef4444; }

.section-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:16px; padding:22px; margin-bottom:24px;
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
.empty-state { text-align:center; padding:60px 20px; color:#475569; }
.empty-icon  { font-size:56px; margin-bottom:16px; opacity:0.35; }
</style>

<div class="currency-wrapper">

{{-- PAGE HEADER --}}
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <div class="page-title">💱 Currency Intelligence</div>
        <p class="text-secondary mb-0" style="font-size:13px;">Real-time Exchange Rate & Currency Volatility Monitor</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <span class="badge" style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);color:#38bdf8;font-size:11px;">
            🕐 {{ now()->format('d M Y H:i') }}
        </span>
        <span class="badge" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#86efac;font-size:11px;">
            🟢 ER-API · USD Base
        </span>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Currencies Tracked</div>
            <div class="kpi-value">{{ $totalTracked }}</div>
            <div class="kpi-sub">Exchange rates</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-success">
            <div class="kpi-label">Cost Stable</div>
            <div class="kpi-value">{{ $costStable }}</div>
            <div class="kpi-sub">🟢 Stable</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-warning">
            <div class="kpi-label">Cost Warning</div>
            <div class="kpi-value">{{ $costWarning }}</div>
            <div class="kpi-sub">🟡 Warning</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-danger">
            <div class="kpi-label">Critical / Surge</div>
            <div class="kpi-value">{{ $costSurge + $tradeCritical }}</div>
            <div class="kpi-sub">🔴 High Alert</div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="section-card mb-4">
    <form method="GET" action="{{ route('currency.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Search Currency / Country</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Currency code or country..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Region</label>
                <select name="region" class="form-select form-select-sm">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Currency Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill" style="border-radius:8px;">
                    🔍 Filter
                </button>
                <a href="{{ route('currency.index') }}" class="btn btn-sm flex-fill" style="background:#1e293b;color:#94a3b8;border:1px solid #334155;border-radius:8px;">
                    ✕ Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- CURRENCY TABLE --}}
<div class="section-card">
    <div class="section-title">
        📊 Exchange Rate Dashboard
        <span class="badge-pill">{{ $currencies->total() }} records</span>
    </div>

    @if($currencies->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">💱</div>
            <h5 style="color:#475569;">No Currency Data Available</h5>
            <p class="text-secondary">Currency data will appear here after syncing with the exchange rate API.</p>
            <p class="text-secondary" style="font-size:12px;">Open any country's Intelligence Center and click "Sync Currency" to start.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0"
                   style="--bs-table-bg:#0f172a;--bs-table-border-color:#1e293b;--bs-table-hover-bg:#1e293b;">
                <thead>
                    <tr style="border-bottom:1px solid #1e293b;">
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">#</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Country</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Region</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Currency</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Rate (USD)</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Change %</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Status</th>
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;letter-spacing:0.8px;padding:10px 12px;background:#020617;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currencies as $cur)
                    @php
                        $change = $cur->change_percent ?? 0;
                        $changeColor = $change > 5 ? '#ef4444' : ($change > 2 ? '#f59e0b' : '#22c55e');
                        $changeIcon  = $change > 0 ? '↑' : ($change < 0 ? '↓' : '→');

                        $statusBg = match($cur->currency_status) {
                            'Trade Critical' => 'rgba(239,68,68,0.12)',
                            'Cost Surge'     => 'rgba(245,158,11,0.2)',
                            'Cost Warning'   => 'rgba(245,158,11,0.12)',
                            default          => 'rgba(34,197,94,0.12)',
                        };
                        $statusColor = match($cur->currency_status) {
                            'Trade Critical' => '#ef4444',
                            'Cost Surge'     => '#f59e0b',
                            'Cost Warning'   => '#fcd34d',
                            default          => '#22c55e',
                        };
                        $statusIcon = match($cur->currency_status) {
                            'Trade Critical' => '🔴',
                            'Cost Surge'     => '🟠',
                            'Cost Warning'   => '🟡',
                            default          => '🟢',
                        };
                    @endphp
                    <tr>
                        <td style="color:#64748b;font-size:12px;padding:10px 12px;">{{ $currencies->firstItem() + $loop->index }}</td>
                        <td style="padding:10px 12px;">
                            <div class="d-flex align-items-center gap-2">
                                @if($cur->country?->flag)
                                    <img src="{{ $cur->country->flag }}" width="28" height="20" style="border-radius:3px;object-fit:cover;" onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:#f1f5f9;">{{ $cur->country?->name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:10px 12px;"><span style="font-size:11px;color:#64748b;">{{ $cur->country?->region ?? '—' }}</span></td>
                        <td style="padding:10px 12px;">
                            <span style="font-size:14px;font-weight:700;color:#38bdf8;">{{ $cur->currency_code }}</span>
                            <div style="font-size:10px;color:#64748b;">{{ $cur->base_currency ?? 'USD' }} base</div>
                        </td>
                        <td style="padding:10px 12px;">
                            <span style="font-size:14px;font-weight:700;color:#f8fafc;">
                                {{ number_format($cur->exchange_rate, 4) }}
                            </span>
                        </td>
                        <td style="padding:10px 12px;">
                            <span style="font-size:13px;font-weight:700;color:{{ $changeColor }};">
                                {{ $changeIcon }} {{ number_format(abs($change), 2) }}%
                            </span>
                        </td>
                        <td style="padding:10px 12px;">
                            <span style="background:{{ $statusBg }};color:{{ $statusColor }};border:1px solid {{ $statusColor }}33;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">
                                {{ $statusIcon }} {{ $cur->currency_status ?? 'Cost Stable' }}
                            </span>
                        </td>
                        <td style="padding:10px 12px;">
                            @if($cur->country)
                                <a href="{{ route('countries.show', $cur->country->id) }}"
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
        @if($currencies->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top:1px solid #1e293b;">
                <div style="font-size:12px;color:#64748b;">
                    Showing {{ $currencies->firstItem() }}–{{ $currencies->lastItem() }} of {{ $currencies->total() }} records
                </div>
                {{ $currencies->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>

{{-- TOP STRONG / WEAK --}}
@php
    $strongCurrencies = \App\Models\CurrencyRate::with('country')
        ->whereNotNull('exchange_rate')
        ->where('currency_status', 'Stable')
        ->orderBy('exchange_rate')
        ->limit(5)
        ->get();

    $weakCurrencies = \App\Models\CurrencyRate::with('country')
        ->whereNotNull('exchange_rate')
        ->whereIn('currency_status', ['Critical', 'Warning'])
        ->orderByDesc('exchange_rate')
        ->limit(5)
        ->get();
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <div class="section-card" style="margin-bottom:0;">
            <div class="section-title">🟢 Top Strong Currencies <span class="badge-pill">Stable</span></div>
            @forelse($strongCurrencies as $sc)
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(30,41,59,0.5);">
                    <div class="d-flex align-items-center gap-2">
                        @if($sc->country?->flag)
                            <img src="{{ $sc->country->flag }}" width="22" height="15" style="border-radius:2px;object-fit:cover;" onerror="this.style.display='none'">
                        @endif
                        <div>
                            <span style="font-size:13px;font-weight:600;color:#f1f5f9;">{{ $sc->currency_code }}</span>
                            <span style="font-size:11px;color:#64748b;margin-left:6px;">{{ $sc->country?->name }}</span>
                        </div>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#22c55e;">{{ number_format($sc->exchange_rate, 4) }}</span>
                </div>
            @empty
                <p class="text-secondary" style="font-size:13px;">No stable currency data yet.</p>
            @endforelse
        </div>
    </div>
    <div class="col-md-6">
        <div class="section-card" style="margin-bottom:0;">
            <div class="section-title">🔴 Top Volatile Currencies <span class="badge-pill">High Risk</span></div>
            @forelse($weakCurrencies as $wc)
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(30,41,59,0.5);">
                    <div class="d-flex align-items-center gap-2">
                        @if($wc->country?->flag)
                            <img src="{{ $wc->country->flag }}" width="22" height="15" style="border-radius:2px;object-fit:cover;" onerror="this.style.display='none'">
                        @endif
                        <div>
                            <span style="font-size:13px;font-weight:600;color:#f1f5f9;">{{ $wc->currency_code }}</span>
                            <span style="font-size:11px;color:#64748b;margin-left:6px;">{{ $wc->country?->name }}</span>
                        </div>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#ef4444;">{{ number_format($wc->exchange_rate, 4) }}</span>
                </div>
            @empty
                <p class="text-secondary" style="font-size:13px;">No volatile currency data yet.</p>
            @endforelse
        </div>
    </div>
</div>

</div>

</x-dashboard-layout>
