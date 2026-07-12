<x-app-layout>


<x-slot name="header">

<h2>
Global Supply Chain Risk Map
</h2>

</x-slot>


<div class="container-fluid mt-4">


<div class="card shadow">


<div class="card-body">


<div id="world-map"
style="height:600px;">
</div>


</div>


</div>


</div>



<script>


document.addEventListener(
'DOMContentLoaded',
function(){


let map = L.map('world-map')
.setView(
[20,0],
2
);



L.tileLayer(
'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
{

attribution:
'OpenStreetMap'

}

).addTo(map);



let countries = @json($countries);



countries.forEach(country=>{


let marker = L.marker([

country.latitude,

country.longitude

])
.addTo(map);



marker.bindPopup(`


<h5>
${country.name}
</h5>


<a href="/countries/${country.id}">
View Detail
</a>


`);



});



});


</script>


</x-app-layout>