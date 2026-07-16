<x-dashboard-layout>

@section('title', 'Admin Panel - Countries')

<style>
.countries-wrapper { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
.section-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:18px; padding:22px; margin-bottom:24px;
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
</style>

<div class="countries-wrapper">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="page-title">🌎 Country Database Administration</div>
            <p class="text-secondary mb-0" style="font-size:13px;">Monitor database statistics and sync triggers per country</p>
        </div>
        <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">← Overview</a>
    </div>

    {{-- SEARCH BAR --}}
    <div class="section-card">
        <form method="GET" action="{{ route('admin.countries') }}">
            <div class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by country name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-sm btn-primary w-100" style="border-radius:8px;">🔍 Search</button>
                </div>
            </div>
        </form>
    </div>

    {{-- COUNTRIES DATA TABLE --}}
    <div class="section-card">
        <div class="section-title">
            📋 Country Objects
            <span class="badge-pill">{{ $countries->total() }} countries</span>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="font-size:13px; --bs-table-bg:#0f172a;">
                <thead>
                    <tr style="border-bottom:1px solid #1e293b;">
                        <th>Flag</th>
                        <th>Name</th>
                        <th>Region</th>
                        <th>Risk Level</th>
                        <th>Risk Score</th>
                        <th>GDP Status</th>
                        <th>Weather</th>
                        <th>Currency</th>
                        <th class="text-end">Intelligence Center</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $c)
                        @php
                            $risk = $c->riskScores->first();
                            $eco = $c->economicData->first();
                            $wea = $c->weatherData->first();
                            $cur = $c->currencyRates->first();
                        @endphp
                        <tr>
                            <td>
                                @if($c->flag)
                                    <img src="{{ $c->flag }}" width="24" height="16" style="border-radius:2px; object-fit:cover;">
                                @else
                                    <span style="font-size:16px;">🌎</span>
                                @endif
                            </td>
                            <td><strong class="text-white">{{ $c->name }}</strong> <code class="text-secondary" style="font-size:10.5px;">{{ $c->iso2 }}</code></td>
                            <td class="text-secondary">{{ $c->region ?? '—' }}</td>
                            <td>
                                @if($risk)
                                    <span class="badge @if($risk->risk_level == 'High Risk') bg-danger @elseif($risk->risk_level == 'Medium Risk') bg-warning text-dark @else bg-success @endif">
                                        {{ $risk->risk_level }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Uncalculated</span>
                                @endif
                            </td>
                            <td>
                                @if($risk)
                                    <strong class="text-white">{{ $risk->total_score }}/100</strong>
                                @else
                                    <span class="text-secondary">—</span>
                                @endif
                            </td>
                            <td>
                                @if($eco)
                                    <span class="text-success">GDP: ${{ number_format($eco->gdp / 1e9, 1) }}B</span>
                                @else
                                    <span class="text-secondary">No Data</span>
                                @endif
                            </td>
                            <td>
                                @if($wea)
                                    <span class="text-info">{{ $wea->temperature }}°C ({{ $wea->weather_status }})</span>
                                @else
                                    <span class="text-secondary">No Data</span>
                                @endif
                            </td>
                            <td>
                                @if($cur)
                                    <span class="text-warning">{{ $cur->currency_code }} ({{ number_format($cur->exchange_rate, 2) }})</span>
                                @else
                                    <span class="text-secondary">No Data</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('countries.show', $c->id) }}" class="btn btn-sm btn-outline-primary py-1" style="font-size:11px; border-radius:8px;">
                                    🔍 Show
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($countries->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div style="font-size:12px; color:#64748b;">
                    Showing {{ $countries->firstItem() }}–{{ $countries->lastItem() }} of {{ $countries->total() }} records
                </div>
                {{ $countries->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

</x-dashboard-layout>
