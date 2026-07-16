<x-dashboard-layout>

@section('title', 'Country Comparison')

<style>
.comparison-wrapper { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
.section-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:18px; padding:24px; margin-bottom:24px;
}
.section-title {
    font-size:15px; font-weight:700; color:#f8fafc;
    margin-bottom:20px; display:flex; align-items:center; gap:8px;
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
.comparison-table td {
    vertical-align: middle;
    padding:12px;
}
.comparison-table tr {
    border-bottom:1px solid rgba(30,41,59,0.5);
}
</style>

<div class="comparison-wrapper">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="page-title">⚖️ Country Comparison Engine</div>
            <p class="text-secondary mb-0" style="font-size:13px;">Compare economic, risk, and weather indicators between two countries</p>
        </div>
    </div>

    {{-- COMPARISON SELECTOR --}}
    <div class="section-card">
        <form method="GET" action="{{ route('comparison.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-secondary fw-semibold" style="font-size:12px; text-transform:uppercase;">Country A</label>
                    <select name="country_a" class="form-select" required>
                        <option value="">Select First Country</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ request('country_a') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->region }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-center pb-2">
                    <span class="fs-4 text-primary fw-bold">VS</span>
                </div>
                <div class="col-md-5">
                    <label class="form-label text-secondary fw-semibold" style="font-size:12px; text-transform:uppercase;">Country B</label>
                    <select name="country_b" class="form-select" required>
                        <option value="">Select Second Country</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ request('country_b') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->region }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary" style="border-radius:10px; padding:8px 24px; font-weight:600;">
                        📊 Compare Indicators
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($countryA && $countryB)
        {{-- COMPARISON RESULTS --}}
        <div class="row g-4 mb-4">
            {{-- STATS TABLE --}}
            <div class="col-lg-7">
                <div class="section-card h-100">
                    <div class="section-title">
                        📊 Side-by-Side Comparison
                        <span class="badge-pill">Metrics</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark table-borderless comparison-table mb-0">
                            <thead>
                                <tr style="border-bottom:1px solid #1e293b;">
                                    <th style="color:#64748b; font-size:11px;">Indicator</th>
                                    <th style="font-size:13px; color:#38bdf8;">
                                        @if($countryA->flag)
                                            <img src="{{ $countryA->flag }}" width="20" height="14" style="border-radius:2px; margin-right:4px;">
                                        @endif
                                        {{ $countryA->name }}
                                    </th>
                                    <th style="font-size:13px; color:#38bdf8;">
                                        @if($countryB->flag)
                                            <img src="{{ $countryB->flag }}" width="20" height="14" style="border-radius:2px; margin-right:4px;">
                                        @endif
                                        {{ $countryB->name }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $riskA = $countryA->riskScores->first();
                                    $riskB = $countryB->riskScores->first();
                                    
                                    $ecoA = $countryA->economicData->first();
                                    $ecoB = $countryB->economicData->first();

                                    $weaA = $countryA->weatherData->first();
                                    $weaB = $countryB->weatherData->first();

                                    $curA = $countryA->currencyRates->first();
                                    $curB = $countryB->currencyRates->first();

                                    $recA = $countryA->recommendations->first();
                                    $recB = $countryB->recommendations->first();
                                @endphp
                                <tr>
                                    <td class="text-secondary">Overall Risk Level</td>
                                    <td>
                                        <span class="badge @if(($riskA?->risk_level ?? '') == 'High Risk') bg-danger @elseif(($riskA?->risk_level ?? '') == 'Medium Risk') bg-warning text-dark @else bg-success @endif">
                                            {{ $riskA?->risk_level ?? 'Low Risk' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge @if(($riskB?->risk_level ?? '') == 'High Risk') bg-danger @elseif(($riskB?->risk_level ?? '') == 'Medium Risk') bg-warning text-dark @else bg-success @endif">
                                            {{ $riskB?->risk_level ?? 'Low Risk' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Risk Score</td>
                                    <td class="fw-bold fs-5" style="color:{{ ($riskA?->risk_level ?? '') == 'High Risk' ? '#ef4444' : (($riskA?->risk_level ?? '') == 'Medium Risk' ? '#f59e0b' : '#22c55e') }};">
                                        {{ $riskA?->total_score ?? '20' }} / 100
                                    </td>
                                    <td class="fw-bold fs-5" style="color:{{ ($riskB?->risk_level ?? '') == 'High Risk' ? '#ef4444' : (($riskB?->risk_level ?? '') == 'Medium Risk' ? '#f59e0b' : '#22c55e') }};">
                                        {{ $riskB?->total_score ?? '20' }} / 100
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">GDP</td>
                                    <td>{{ $ecoA?->gdp ? '$' . number_format($ecoA->gdp / 1e9, 2) . 'B' : '—' }}</td>
                                    <td>{{ $ecoB?->gdp ? '$' . number_format($ecoB->gdp / 1e9, 2) . 'B' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Inflation Rate</td>
                                    <td class="fw-semibold text-warning">{{ $ecoA?->inflation !== null ? $ecoA->inflation . '%' : '—' }}</td>
                                    <td class="fw-semibold text-warning">{{ $ecoB?->inflation !== null ? $ecoB->inflation . '%' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Population</td>
                                    <td>{{ $ecoA?->population ? number_format($ecoA->population) : '—' }}</td>
                                    <td>{{ $ecoB?->population ? number_format($ecoB->population) : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Temperature</td>
                                    <td class="text-info">{{ $weaA?->temperature !== null ? $weaA->temperature . '°C' : '—' }}</td>
                                    <td class="text-info">{{ $weaB?->temperature !== null ? $weaB->temperature . '°C' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Weather Status</td>
                                    <td>{{ $weaA?->weather_status ?? 'Normal' }}</td>
                                    <td>{{ $weaB?->weather_status ?? 'Normal' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Currency</td>
                                    <td>{{ $curA?->currency_code ?? $countryA->currency_code }} ({{ number_format($curA?->exchange_rate ?? 1.0, 4) }} / USD)</td>
                                    <td>{{ $curB?->currency_code ?? $countryB->currency_code }} ({{ number_format($curB?->exchange_rate ?? 1.0, 4) }} / USD)</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Port Count</td>
                                    <td><span class="badge bg-secondary">{{ $countryA->ports->count() }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $countryB->ports->count() }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Recommendation</td>
                                    <td style="font-size:12px;" class="text-secondary">{{ $recA?->recommendation ?? 'Proceed with caution' }}</td>
                                    <td style="font-size:12px;" class="text-secondary">{{ $recB?->recommendation ?? 'Proceed with caution' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RADAR CHART --}}
            <div class="col-lg-5">
                <div class="section-card h-100">
                    <div class="section-title">
                        🎯 Risk Factor Radar
                        <span class="badge-pill">Weather vs Inflation vs News vs Currency</span>
                    </div>
                    <div style="height:320px; position:relative;">
                        <canvas id="comparisonRadarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->filled('country_a'))
        <div class="alert alert-warning">
            ⚠️ Please select both Country A and Country B to compare.
        </div>
    @endif

</div>

{{-- SCRIPT INTEGRATION --}}
@if($countryA && $countryB)
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('comparisonRadarChart').getContext('2d');
    
    var radarData = {
        labels: ['Weather Risk', 'Inflation Risk', 'Currency Risk', 'News Risk'],
        datasets: [
            {
                label: '{{ $countryA->name }}',
                data: [
                    {{ $riskA?->weather_risk ?? 20 }},
                    {{ $riskA?->inflation_risk ?? 20 }},
                    {{ $riskA?->currency_risk ?? 20 }},
                    {{ $riskA?->news_risk ?? 20 }}
                ],
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: '#3b82f6',
                borderWidth: 2,
                pointBackgroundColor: '#3b82f6'
            },
            {
                label: '{{ $countryB->name }}',
                data: [
                    {{ $riskB?->weather_risk ?? 20 }},
                    {{ $riskB?->inflation_risk ?? 20 }},
                    {{ $riskB?->currency_risk ?? 20 }},
                    {{ $riskB?->news_risk ?? 20 }}
                ],
                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                borderColor: '#ef4444',
                borderWidth: 2,
                pointBackgroundColor: '#ef4444'
            }
        ]
    };

    new Chart(ctx, {
        type: 'radar',
        data: radarData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    pointLabels: { color: '#64748b', font: { size: 11 } },
                    ticks: { color: '#64748b', backdropColor: 'transparent', stepSize: 20 },
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: { labels: { color: '#f8fafc' } }
            }
        }
    });
});
</script>
@endif

</x-dashboard-layout>
