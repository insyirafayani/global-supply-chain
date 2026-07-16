<x-dashboard-layout>

<div class="mb-4">

<a href="{{ route('countries.index') }}"
class="btn btn-outline-info mb-4">

← Back to Country Monitor

</a>

<h1 style="color:#38bdf8">

🌍 {{ $country->name }}

</h1>

<p class="text-secondary">

Global Supply Chain Intelligence Center

</p>
<!-- NAVIGATION -->

<ul class="nav nav-pills mb-4" id="countryTabs">

<li class="nav-item">

<a class="nav-link active"

href="#overview"

data-bs-toggle="tab">

🏠 Overview

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#economic"

data-bs-toggle="tab">

📈 Economic

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#weather"

data-bs-toggle="tab">

☁ Weather

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#currency"

data-bs-toggle="tab">

💱 Currency

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#news"

data-bs-toggle="tab">

📰 News

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#risk"

data-bs-toggle="tab">

🚨 Risk

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#recommendation"

data-bs-toggle="tab">

⭐ Recommendation

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="#trade"

data-bs-toggle="tab">

🚢 Trade

</a>

</li>

</ul>

</div>

<div class="tab-content">

<div
class="tab-pane fade show active"
id="overview">

@include('countries.sections.profile')

</div>

<div
class="tab-pane fade"
id="economic">

@include('countries.sections.economic')

</div>

<div
class="tab-pane fade"
id="weather">

@include('countries.sections.weather')

</div>

<div
class="tab-pane fade"
id="currency">

@include('countries.sections.currency')

</div>

<div
class="tab-pane fade"
id="news">

@include('countries.sections.news')

</div>

<div
class="tab-pane fade"
id="risk">

@include('countries.sections.risk')

</div>

<div
class="tab-pane fade"
id="recommendation">

@include('countries.sections.recommendation')

</div>

<div
class="tab-pane fade"
id="trade">

@include('countries.sections.trade')

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = {{ $country->latitude ?? 'null' }};
    var lng = {{ $country->longitude ?? 'null' }};
    
    if (lat === null || lng === null) {
        lat = 0;
        lng = 0;
    }

    const map = L.map('countryMap').setView([lat, lng], 6);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        maxZoom: 18
    }).addTo(map);

    // Fix map render size issue inside bootstrap tab
    setTimeout(function() { 
        map.invalidateSize(); 
        map.flyTo([lat, lng], 6, { animate: true, duration: 1.5 });
    }, 400);

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        if (e.target.getAttribute('href') === '#overview') {
            map.invalidateSize();
            map.setView([lat, lng], map.getZoom());
        }
    });

    @php
        $risk = $country->riskScores->first();
        $eco = $country->economicData->first();
        $riskLevel = $risk ? $risk->risk_level : 'Unknown';
        $gdp = $eco && $eco->gdp ? '$' . number_format($eco->gdp / 1e9, 1) . 'B' : '—';
        $pop = $eco && $eco->population ? number_format($eco->population) : '—';
    @endphp

    var cName = @json($country->name);
    var cCapital = @json($country->capital ?? '—');
    var cRegion = @json($country->region ?? '—');
    var cRisk = @json($riskLevel);
    var cGdp = @json($gdp);
    var cPop = @json($pop);

    var riskColor = '#27AE60';
    if(cRisk === 'High Risk') riskColor = '#EB5757';
    if(cRisk === 'Medium Risk') riskColor = '#F2C94C';

    var countryIcon = L.divIcon({
        html: '<div style="font-size: 36px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5)); text-align: center; line-height: 1;">📍</div>',
        className: 'custom-country-icon',
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -36]
    });

    var countryMarker = L.marker([lat, lng], {
        icon: countryIcon,
        zIndexOffset: 1000 // Ensure country marker is always on top
    }).addTo(map);

    var countryPopup = '<div style="color:#0f172a; font-family:Inter,sans-serif; min-width:200px;">'
        + '<h6 style="margin:0 0 5px 0; font-weight:800; font-size:15px;">' + cName + '</h6><hr style="margin:5px 0;">'
        + '<b>Capital:</b> ' + cCapital + '<br>'
        + '<b>Region:</b> ' + cRegion + '<br>'
        + '<b>Latitude:</b> ' + parseFloat(lat).toFixed(4) + '<br>'
        + '<b>Longitude:</b> ' + parseFloat(lng).toFixed(4) + '<br>'
        + '<b>Risk Level:</b> <span style="color:' + riskColor + ';">' + cRisk + '</span><br>'
        + '<b>GDP:</b> ' + cGdp + '<br>'
        + '<b>Population:</b> ' + cPop
        + '</div>';
        
    countryMarker.bindPopup(countryPopup).openPopup();

    var ports = @json($country->ports);
    if (ports && ports.length > 0) {
        var portGroup = L.featureGroup().addTo(map);
        
        var portIcon = L.divIcon({
            html: '<div style="font-size: 22px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8)); text-align: center; line-height: 1;">⚓</div>',
            className: 'custom-port-icon',
            iconSize: [22, 22],
            iconAnchor: [11, 11],
            popupAnchor: [0, -11]
        });

        ports.forEach(function(port) {
            if (port.latitude && port.longitude) {
                var pMarker = L.marker([port.latitude, port.longitude], {
                    icon: portIcon
                }).addTo(portGroup);

                var statusColor = port.status === 'Open' ? '#27AE60' : '#EB5757';
                var pPopup = '<div style="color:#0f172a; font-family:Inter,sans-serif; min-width:200px;">'
                    + '<h6 style="margin:0 0 5px 0; font-weight:700; font-size:14px;">⚓ ' + port.port_name + '</h6><hr style="margin:5px 0;">'
                    + '<b>Type:</b> ' + (port.port_type || '—') + '<br>'
                    + '<b>Status:</b> <span style="color:' + statusColor + ';">' + (port.status || '—') + '</span><br>'
                    + '<b>Risk Level:</b> ' + (port.risk || '—') + '<br>'
                    + '<b>Congestion:</b> ' + (port.congestion || '—') + '<br>'
                    + '<b>Coords:</b> ' + parseFloat(port.latitude).toFixed(4) + ', ' + parseFloat(port.longitude).toFixed(4)
                    + '</div>';
                    
                pMarker.bindPopup(pPopup);
            }
        });
        
        setTimeout(function() {
            var groupBounds = portGroup.getBounds();
            groupBounds.extend([lat, lng]); 
            map.fitBounds(groupBounds.pad(0.15));
            setTimeout(function() { countryMarker.openPopup(); }, 300);
        }, 800);
    }
});
</script>

</x-dashboard-layout>