<x-dashboard-layout>


<style>
.exec-summary-panel {
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.stat-card-premium {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    border: 1px solid #1e293b;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.stat-card-premium::after {
    content:'';
    position:absolute;
    top:-30px; right:-30px;
    width:100px; height:100px;
    border-radius:50%;
    opacity:0.07;
    background: #38bdf8;
}
.stat-card-premium:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 0 40px rgba(37,99,235,0.12);
}

.kpi-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }
.kpi-value { font-size: 32px; font-weight: 800; line-height: 1; color: #f8fafc; }

.kpi-danger .kpi-value  { color: #ef4444; }
.kpi-warning .kpi-value { color: #f59e0b; }
.kpi-success .kpi-value { color: #22c55e; }
.kpi-info .kpi-value    { color: #38bdf8; }

.kpi-danger::after  { background: #ef4444; }
.kpi-warning::after { background: #f59e0b; }
.kpi-success::after { background: #22c55e; }

@keyframes pulse-green {
    0% { transform: scale(0.95); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(0.95); opacity: 1; }
}
</style>

<div class="exec-summary-panel">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="mb-1" style="font-size: 24px; font-weight: 800; background: linear-gradient(135deg, #38bdf8, #2563eb); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">🌍 Executive Country Intelligence</h2>
            <p class="text-secondary mb-0" style="font-size: 13px;">Enterprise Monitoring Summary &bull; Global Trade Intelligence Platform</p>
        </div>
        <div class="text-md-end mt-3 mt-md-0">
            <div class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 6px 12px; font-size: 11px; font-weight: 600;">
                <span style="display:inline-block; width:6px; height:6px; background:#34d399; border-radius:50%; margin-right:4px; box-shadow: 0 0 8px #34d399; animation: pulse-green 2s infinite;"></span> LIVE Realtime Data
            </div>
            <div class="text-secondary mt-2" style="font-size: 11px;">
                <i class="far fa-clock"></i> Last Sync: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-info">
                <div class="kpi-label"><span>🌍 Countries</span> <span class="badge" style="background: rgba(255,255,255,0.05); color:#64748b;">Monitored</span></div>
                <div class="kpi-value">{{ $totalCountries }}</div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-info">
                <div class="kpi-label"><span>📈 Economic Data</span> <span class="badge" style="background: rgba(255,255,255,0.05); color:#64748b;">98%</span></div>
                <div class="kpi-value">{{ number_format($totalEconomic) }}</div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-info">
                <div class="kpi-label"><span>☁ Weather Data</span> <span class="badge" style="background: rgba(255,255,255,0.05); color:#64748b;">Realtime</span></div>
                <div class="kpi-value">{{ number_format($totalWeather) }}</div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-info">
                <div class="kpi-label"><span>💱 Currency Data</span> <span class="badge" style="background: rgba(255,255,255,0.05); color:#64748b;">Updated</span></div>
                <div class="kpi-value">{{ number_format($totalCurrency) }}</div>
            </div>
        </div>
        <!-- Card 5 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-info">
                <div class="kpi-label"><span>📰 News Articles</span> <span class="badge" style="background: rgba(255,255,255,0.05); color:#64748b;">Today</span></div>
                <div class="kpi-value">{{ number_format($totalNews) }}</div>
            </div>
        </div>
        <!-- Card 6 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-success">
                <div class="kpi-label"><span>🟢 Low Risk</span> <span class="badge" style="background: rgba(34,197,94,0.1); color:#22c55e;">Safe</span></div>
                <div class="kpi-value">{{ $lowRisk }}</div>
            </div>
        </div>
        <!-- Card 7 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-warning">
                <div class="kpi-label"><span>🟡 Medium Risk</span> <span class="badge" style="background: rgba(245,158,11,0.1); color:#f59e0b;">Watch</span></div>
                <div class="kpi-value">{{ $mediumRisk }}</div>
            </div>
        </div>
        <!-- Card 8 -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card-premium kpi-danger">
                <div class="kpi-label"><span>🔴 High Risk</span> <span class="badge" style="background: rgba(239,68,68,0.1); color:#ef4444;">Alert</span></div>
                <div class="kpi-value">{{ $highRisk }}</div>
            </div>
        </div>
    </div>
</div>



<!-- SEARCH -->


<div class="card card-dark dashboard-card p-4 mb-4">


<form method="GET" action="{{ route('countries.index') }}">


<div class="row">


<div class="col-md-5">


<input

type="text"

name="search"

class="form-control"

placeholder="Search country..."

value="{{ request('search') }}"

>


</div>




<div class="col-md-4">


<select name="region"

class="form-select">


<option value="">

All Region

</option>



@foreach($regions as $region)


<option value="{{ $region }}"

{{ request('region') == $region ? 'selected':'' }}

>

{{ $region }}

</option>


@endforeach



</select>


</div>





<div class="col-md-3">


<button type="submit"

class="btn btn-primary w-100">


🔍 Search


</button>


</div>



</div>


</form>


</div>







<!-- COUNTRY LIST -->


<div class="row">



@foreach($countries as $country)

@php

$risk = $country->riskScores->last();

$economic = $country->economicData->last();

$weather = $country->weatherData->last();

$currency = $country->currencyRates->last();

$recommendation = $country->recommendations->last();

$totalNews = $country->newsCaches->count();

@endphp

<div class="col-lg-4 col-md-6 mb-4">

<div class="card card-dark dashboard-card h-100">

<div class="card-body">

<div class="d-flex justify-content-between">

<div class="d-flex">

@if($country->iso2)

<img src="https://flagcdn.com/w40/{{ strtolower($country->iso2) }}.png" width="36" height="24" class="rounded shadow-sm me-3 mt-1" style="object-fit:cover; border: 1px solid rgba(255,255,255,0.1);" alt="{{ $country->name }}">

@elseif($country->flag)

<img src="{{ $country->flag }}" width="36" height="24" class="rounded shadow-sm me-3 mt-1" style="object-fit:cover; border: 1px solid rgba(255,255,255,0.1);" alt="{{ $country->name }}">

@else

<div style="font-size:24px; width:36px; text-align:center;" class="me-3 mt-1">🏳️</div>

@endif

<div>

<h5 class="mb-1">

{{ $country->name }}

</h5>

<small class="text-secondary">

{{ $country->official_name ?? '-' }}

</small>

</div>

</div>

<div>

@if($risk)

@if($risk->risk_level=="Low Risk")

<span class="badge bg-success">

Low

</span>

@elseif($risk->risk_level=="Medium Risk")

<span class="badge bg-warning text-dark">

Medium

</span>

@else

<span class="badge bg-danger">

High

</span>

@endif

@else

<span class="badge bg-secondary">

N/A

</span>

@endif

</div>

</div>

<hr>

<div class="row text-center">

<div class="col-6 mb-3">

<small class="text-secondary">

GDP

</small>

<h6>

@if($economic && $economic->gdp > 0)
    @if($economic->gdp >= 1e12)
        ${{ number_format($economic->gdp / 1e12, 2) }} Trillion
    @elseif($economic->gdp >= 1e9)
        ${{ number_format($economic->gdp / 1e9, 2) }} Billion
    @elseif($economic->gdp >= 1e6)
        ${{ number_format($economic->gdp / 1e6, 2) }} Million
    @else
        ${{ number_format($economic->gdp, 2) }}
    @endif
@else
    -
@endif

</h6>

</div>

<div class="col-6 mb-3">

<small class="text-secondary">

Weather

</small>

<h6>

{{ $weather->temperature ?? '--' }}°C

</h6>

</div>

<div class="col-6">

<small class="text-secondary">

Currency

</small>

<h6>

{{ $currency->currency_code ?? '-' }}

</h6>

</div>

<div class="col-6">

<small class="text-secondary">

News

</small>

<h6>

{{ $totalNews }}

Articles

</h6>

</div>

</div>

<hr>

<div class="mb-3">

<small class="text-secondary">

Recommendation

</small>

<div>

{{ $recommendation->recommendation ?? 'No Recommendation Yet' }}

</div>

</div>

<div class="progress mb-3" style="height:7px;">

@php

$score = 0;

if($economic) $score += 20;

if($weather) $score += 20;

if($currency) $score += 20;

if($risk) $score += 20;

if($totalNews>0) $score += 20;

@endphp

<div
class="progress-bar bg-info"
style="width:{{ $score }}%">
</div>

</div>

<small class="text-secondary">

Data Completeness

{{ $score }}%

</small>

<a
href="{{ route('countries.show',$country->id) }}"
class="btn btn-primary w-100 mt-3">

🚀 Open Intelligence Center

</a>

</div>

</div>

</div>

@endforeach



</div>




<div class="mt-4">

{{ $countries->links() }}

</div>



</x-dashboard-layout>