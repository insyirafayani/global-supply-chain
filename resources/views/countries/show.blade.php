<x-app-layout>


<x-slot name="header">

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $country->name }} Monitoring
    </h2>

</x-slot>



<div class="container-fluid mt-4">


<!-- COUNTRY INFORMATION -->

<div class="card shadow mb-4">

<div class="card-body">


<h3>
{{ $country->official_name }}
</h3>


<table class="table table-bordered">


<tr>
<th width="30%">Capital</th>
<td>{{ $country->capital ?? '-' }}</td>
</tr>


<tr>
<th>Region</th>
<td>{{ $country->region ?? '-' }}</td>
</tr>


<tr>
<th>Currency</th>
<td>
{{ $country->currency ?? '-' }}
({{ $country->currency_code ?? '-' }})
</td>
</tr>


<tr>
<th>Language</th>
<td>
{{ $country->language ?? '-' }}
</td>
</tr>


<tr>
<th>Coordinate</th>
<td>
{{ $country->latitude }},
{{ $country->longitude }}
</td>
</tr>


</table>


</div>

</div>





<!-- ECONOMIC INTELLIGENCE -->


<div class="card shadow mb-4">

<div class="card-body">


<h4>
Economic Intelligence
</h4>


<p class="text-muted">
Retrieve GDP, Inflation, Population,
Export and Import data from World Bank API.
</p>



<form method="POST"
action="{{ route('economic.sync',$country->id) }}">

@csrf


<button class="btn btn-success">

Sync Economic Data

</button>


</form>



</div>

</div>





@if($country->economicData->count()>0)


@php

$economy =
$country->economicData->last();

@endphp



<div class="card shadow mb-4">


<div class="card-body">


<h4>
Latest Economic Overview
</h4>



<div class="row">


<div class="col-md-3">

<div class="card bg-light">

<div class="card-body">

<h6>GDP</h6>

<h4>
${{number_format($economy->gdp)}}
</h4>

<small>USD</small>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card bg-light">

<div class="card-body">

<h6>Inflation</h6>

<h4>
{{$economy->inflation}} %
</h4>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card bg-light">

<div class="card-body">

<h6>Population</h6>

<h4>
{{number_format($economy->population)}}
</h4>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card bg-light">

<div class="card-body">

<h6>
Trade Balance
</h6>

<h4>

{{number_format(
$economy->export_value -
$economy->import_value
)}}

</h4>

</div>

</div>

</div>


</div>



</div>

</div>



@endif







<!-- WEATHER INTELLIGENCE -->


<div class="card shadow mb-4">


<div class="card-body">


<h4>
Weather Intelligence
</h4>


<p class="text-muted">

Real-time weather monitoring from Open-Meteo API.

</p>



<form method="POST"
action="{{route('weather.sync',$country->id)}}">


@csrf


<button class="btn btn-primary">

Sync Weather Data

</button>


</form>



</div>


</div>






@if($country->weatherData->count()>0)


@php

$weather =
$country->weatherData->last();

@endphp




<div class="card shadow mb-4">


<div class="card-body">


<h4>
Current Weather Condition
</h4>



<div class="row">



<div class="col-md-4">

<div class="card bg-light">

<div class="card-body">

<h6>
Temperature
</h6>

<h3>
{{$weather->temperature}} °C
</h3>


</div>

</div>

</div>





<div class="col-md-4">

<div class="card bg-light">

<div class="card-body">

<h6>
Rainfall
</h6>

<h3>
{{$weather->rainfall}} mm
</h3>


</div>

</div>

</div>





<div class="col-md-4">

<div class="card bg-light">

<div class="card-body">

<h6>
Weather Status
</h6>

<h3>
{{$weather->weather_status}}
</h3>


</div>

</div>

</div>



</div>




<hr>


<strong>
Wind Speed:
</strong>

{{$weather->wind_speed}} km/h



</div>

</div>



@endif

<!-- CURRENCY INTELLIGENCE -->


<div class="card shadow mb-4">


<div class="card-body">


<h4>
Currency Impact Dashboard
</h4>


<p class="text-muted">

Exchange rate monitoring from ExchangeRate API.

</p>



<form method="POST"
action="{{route('currency.sync',$country->id)}}">


@csrf


<button class="btn btn-warning">

Sync Currency Data

</button>


</form>



</div>


</div>







@if($country->currencyRates->count()>0)


@php

$currency =
$country->currencyRates->last();

@endphp




<div class="card shadow mb-4">


<div class="card-body">


<h4>
Latest Currency Status
</h4>



<div class="row">



<div class="col-md-4">

<div class="card bg-light">

<div class="card-body">

<h6>
Exchange Rate
</h6>

<h3>

{{number_format(
$currency->exchange_rate,
4
)}}

</h3>


<small>
1 USD =
{{$currency->currency_code}}
</small>


</div>

</div>

</div>





<div class="col-md-4">

<div class="card bg-light">

<div class="card-body">

<h6>
Change
</h6>

<h3>

{{$currency->change_percent}} %

</h3>


</div>

</div>

</div>





<div class="col-md-4">

<div class="card bg-light">

<div class="card-body">

<h6>
Currency Status
</h6>


<h3>

{{$currency->currency_status}}

</h3>


</div>

</div>

</div>



</div>



</div>

</div>



@endif

<!-- NEWS INTELLIGENCE -->


@if($country->news->count() > 0)


<div class="card shadow mb-4">


<div class="card-body">


<h4 class="mb-3">

News Intelligence

</h4>



<div class="row">


@foreach($country->news->take(6) as $news)



<div class="col-md-6 mb-3">


<div class="card border">


<div class="card-body">


<h5>

{{ $news->title }}

</h5>



<p class="text-muted">

{{ $news->source }}

</p>



<p>

{{ Str::limit(
$news->description,
120
) }}

</p>



@if($news->sentiment == 'Positive')


<span class="badge bg-success">
Positive
</span>


@elseif($news->sentiment == 'Negative')


<span class="badge bg-danger">
Negative
</span>


@else


<span class="badge bg-secondary">
Neutral
</span>


@endif




<div class="mt-2">


<small>

Positive Score:
{{ $news->positive_score }}

|

Negative Score:
{{ $news->negative_score }}

</small>


</div>



</div>


</div>


</div>



@endforeach


</div>


</div>


</div>


@endif

</div>


</x-app-layout>