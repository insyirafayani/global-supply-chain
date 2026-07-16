<x-dashboard-layout>


<div class="mb-4">


<h1 style="color:#38bdf8">

🌎 Global Country Monitoring

</h1>


<p class="text-secondary">

Enterprise Country Intelligence Database

</p>


</div>

<div class="row g-3 mb-4">

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">Countries</small>
                <h2>{{ $totalCountries }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">Low Risk</small>
                <h2 class="text-success">{{ $lowRisk }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">Medium Risk</small>
                <h2 class="text-warning">{{ $mediumRisk }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">High Risk</small>
                <h2 class="text-danger">{{ $highRisk }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">Economic Data</small>
                <h2>{{ $totalEconomic }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">Weather Data</small>
                <h2>{{ $totalWeather }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">Currency Data</small>
                <h2>{{ $totalCurrency }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-dark dashboard-card">
            <div class="card-body">
                <small class="text-secondary">News Articles</small>
                <h2>{{ number_format($totalNews) }}</h2>
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

@if($country->flag)

<img
src="{{ $country->flag }}"
width="55"
height="40"
class="rounded shadow me-3"
style="object-fit:cover;">

@else

<div style="font-size:45px" class="me-3">

🌍

</div>

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