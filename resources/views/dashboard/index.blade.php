<x-dashboard-layout>


<div class="mb-4">


<h1 class="fw-bold">

🌍 Executive Dashboard

</h1>


<p class="text-secondary">

Global Supply Chain Risk Intelligence Platform

</p>


</div>





<div class="row g-4">



<!-- TOTAL COUNTRIES -->


<div class="col-md-3">


<div class="card card-dark p-4 dashboard-card">


<div class="d-flex justify-content-between">


<div>


<h6 class="text-secondary">

Total Countries

</h6>


<h2 class="fw-bold">

{{ $totalCountries }}

</h2>


<span class="text-success">

Monitoring

</span>


</div>



<div style="font-size:40px">

🌍

</div>


</div>


</div>


</div>

<!-- RISK ENGINE -->


<div class="col-md-3">


<div class="card card-dark p-4 dashboard-card">


<div class="d-flex justify-content-between">


<div>


<h6 class="text-secondary">

Risk Monitoring

</h6>


<h2 class="fw-bold">

{{ $riskCount }}

</h2>


<span class="text-warning">

Risk Engine

</span>


</div>



<div style="font-size:40px">

⚠️

</div>


</div>


</div>


</div>

<!-- WEATHER -->


<div class="col-md-3">


<div class="card card-dark p-4 dashboard-card">


<div class="d-flex justify-content-between">


<div>


<h6 class="text-secondary">

Weather Monitoring

</h6>


<h2 class="fw-bold">

{{ $weatherCount }}

</h2>


<span class="text-info">

Open-Meteo API

</span>


</div>



<div style="font-size:40px">

☁️

</div>


</div>


</div>


</div>

<!-- API -->


<div class="col-md-3">


<div class="card card-dark p-4 dashboard-card">


<div class="d-flex justify-content-between">


<div>


<h6 class="text-secondary">

External API

</h6>


<h2 class="fw-bold">

{{ $totalNews }}

</h2>


<span class="text-success">

Connected Services

</span>


</div>



<div style="font-size:40px">

🔗

</div>


</div>


</div>


</div>



</div>


<!-- GLOBAL MONITORING AREA -->


<div class="row mt-4">



<div class="col-md-8">


<div class="card card-dark p-4 dashboard-card">


<h4>

🌐 Global Monitoring Map

</h4>


<p class="text-secondary">

Country risk visualization using Leaflet.js

</p>



<div id="globalMap"
style="
height:350px;
border-radius:15px;
overflow:hidden;
">
</div>


</div>


</div>

<div class="col-md-4">


<div class="card card-dark p-4 dashboard-card">


<h4>

☁ Live Weather

</h4>


<hr>


<div class="mb-3">

Indonesia

<br>

<span class="text-secondary">

Temperature Monitoring

</span>

</div>



<div class="mb-3">

Germany

<br>

<span class="text-secondary">

Weather Risk

</span>

</div>



<div>

China

<br>

<span class="text-secondary">

Weather Alert

</span>

</div>


</div>


</div>



</div>





<!-- ANALYTICS -->


<div class="row mt-4">


<div class="col-md-6">


<div class="card card-dark p-4 dashboard-card">


<h4>

📊 Risk Analytics

</h4>


<p class="text-secondary">

Risk score trend visualization

</p>


<div style="
height:200px;
background:#111827;
border-radius:15px;
">


</div>


</div>


</div>





<div class="col-md-6">


<div class="card card-dark p-4 dashboard-card">


<h4>

📰 Global News Intelligence

</h4>


<p class="text-secondary">

News sentiment analysis

</p>


<div>

Positive 🟢

<br>

Neutral ⚪

<br>

Negative 🔴

</div>


</div>


</div>



</div>

<script>


document.addEventListener(
"DOMContentLoaded",
function(){



let map =
L.map('globalMap')
.setView(
[20,0],
2
);



L.tileLayer(
'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
{

maxZoom:18

}
)
.addTo(map);





let countries = @json($countries);


countries.forEach(
function(country){



if(
country.latitude &&
country.longitude
){



let color = "green";


if(country.risk_status == "Medium Risk"){

    color = "orange";

}


if(country.risk_status == "High Risk"){

    color = "red";

}



let marker =
L.circleMarker(

[
country.latitude,
country.longitude
],

{


radius:8,

color:color,

fillColor:color,

fillOpacity:0.8


}

);



marker
.addTo(map)
.bindPopup(

`

<h5>
${country.name}
</h5>


Status:

<b style="color:${color}">

${country.risk_status ?? 'No Risk Data'}

</b>


`

);



}



}

);



}

);


</script>

</x-dashboard-layout>