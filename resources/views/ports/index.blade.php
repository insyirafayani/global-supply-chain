<x-dashboard-layout>

@section('title', 'Port Monitoring')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

<style>
    .ports-wrapper { animation: fadeInUp 0.5s ease both; }
    @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .kpi-card { background:linear-gradient(135deg,#0f172a,#1e293b); border:1px solid #1e293b; border-radius:16px; padding:20px; transition:all .3s ease; position:relative; overflow:hidden; height:100%; }
    .kpi-card:hover { transform:translateY(-4px); border-color:rgba(56,189,248,.35); }
    .kpi-label { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.8px; margin-bottom:8px; }
    .kpi-value { font-size:28px; font-weight:800; line-height:1; color:#f1f5f9; }
    .kpi-value.value-info { color:#38bdf8; } .kpi-value.value-success { color:#22c55e; } .kpi-value.value-warning { color:#f59e0b; } .kpi-value.value-danger { color:#ef4444; }
    .section-card { background:#0f172a; border:1px solid #1e293b; border-radius:16px; padding:22px; margin-bottom:24px; }
    .section-title { font-size:15px; font-weight:700; color:#f8fafc; margin-bottom:18px; display:flex; align-items:center; gap:8px; }
    .badge-pill { font-size:10px; background:rgba(56,189,248,.15); color:#38bdf8; border:1px solid rgba(56,189,248,.25); padding:2px 8px; border-radius:20px; font-weight:600; }
    .page-title { font-size:24px; font-weight:800; background:linear-gradient(135deg,#38bdf8,#2563eb); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    .autocomplete-wrapper { position:relative; }
    .autocomplete-list { position:absolute; top:100%; left:0; right:0; background:#0f172a; border:1px solid #334155; border-top:none; border-radius:0 0 8px 8px; z-index:1100; max-height:200px; overflow-y:auto; margin-top:2px; box-shadow:0 10px 15px -3px rgba(0,0,0,.3); }
    .autocomplete-item { padding:10px 12px; color:#94a3b8; cursor:pointer; font-size:13px; transition:all .2s ease; }
    .autocomplete-item:hover { background:#1e293b; color:#f1f5f9; }
    .detail-panel { background:#0f172a; border:1px solid #1e293b; border-radius:16px; padding:20px; height:520px; overflow-y:auto; color:#f1f5f9; display:flex; flex-direction:column; gap:15px; }
    .detail-header { border-bottom:1px solid #1e293b; padding-bottom:12px; }
    .detail-section { background:rgba(30,41,59,.4); border:1px solid #1e293b; border-radius:8px; padding:12px; }
</style>

<div class="ports-wrapper">

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <div class="page-title">⚓ Port Monitoring Dashboard</div>
        <p class="text-secondary mb-0" style="font-size:13px;">Real-Time World Port Operations, Congestion & Risk Monitor</p>
    </div>
    <span class="badge" style="background:rgba(56,189,248,.1);border:1px solid rgba(56,189,248,.2);color:#38bdf8;font-size:11px;">🕐 Live Intelligence</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2"><div class="kpi-card"><div class="kpi-label">Countries</div><div class="kpi-value value-info">{{ $totalCountries }}</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="kpi-card"><div class="kpi-label">Total Ports</div><div class="kpi-value">{{ $totalPorts }}</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="kpi-card"><div class="kpi-label">Commercial</div><div class="kpi-value value-success">{{ $commercialPorts }}</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="kpi-card"><div class="kpi-label">Container</div><div class="kpi-value value-info">{{ $containerPorts }}</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="kpi-card"><div class="kpi-label">High Risk</div><div class="kpi-value value-danger">{{ $highRiskPorts }}</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="kpi-card"><div class="kpi-label">Congested</div><div class="kpi-value value-warning">{{ $congestedPorts }}</div></div></div>
</div>

<div class="section-card">
    <div class="section-title">🔍 Port Intelligence Filters</div>
    <form method="GET" action="{{ route('ports.index') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-secondary small">Search Country (Loads Markers)</label>
                <div class="autocomplete-wrapper">
                    <input type="text" id="countrySearch" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Type country name..." autocomplete="off">
                    <div id="countryResults" class="autocomplete-list d-none"></div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-secondary small">Search Port (Locate & Open)</label>
                <div class="autocomplete-wrapper">
                    <input type="text" id="portSearch" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Type port name..." autocomplete="off">
                    <div id="portResults" class="autocomplete-list d-none"></div>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label text-secondary small">Port Type</label>
                <select name="port_type" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">All Types</option>
                    @foreach($portTypes as $type)<option value="{{ $type }}" {{ request('port_type')==$type?'selected':'' }}>{{ $type }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-secondary small">Risk Level</label>
                <select name="risk" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">All Risks</option>
                    @foreach($risks as $risk)<option value="{{ $risk }}" {{ request('risk')==$risk?'selected':'' }}>{{ $risk }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-secondary small">Congestion</label>
                <select name="congestion" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">All</option>
                    @foreach($congestions as $cg)<option value="{{ $cg }}" {{ request('congestion')==$cg?'selected':'' }}>{{ $cg }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-secondary small">Region</label>
                <select name="region" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">All Regions</option>
                    @foreach($regions as $rg)<option value="{{ $rg }}" {{ request('region')==$rg?'selected':'' }}>{{ $rg }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-secondary small">Status</label>
                <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)<option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>{{ $st }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">🔍 Filter Table</button>
                <a href="{{ route('ports.index') }}" class="btn btn-sm flex-fill bg-dark text-secondary border-secondary">✕ Clear</a>
            </div>
        </div>
    </form>
</div>

<div class="row g-3">
    <div class="col-lg-9">
        <div class="section-card h-100 p-2" style="position:relative;">
            <div id="portMap" style="height:520px;border-radius:12px;overflow:hidden;background:#0b0f19;"></div>
            <div id="mapLoader" class="position-absolute top-50 start-50 translate-middle d-flex flex-column align-items-center gap-2"
                 style="z-index:1000;background:rgba(15,23,42,.85);padding:20px;border-radius:12px;border:1px solid #334155;">
                <div class="spinner-border text-info" role="status"></div>
                <span class="text-info small fw-bold" id="loaderText">Initializing map...</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="detail-panel">
            <div class="text-center my-auto text-secondary" id="panelPlaceholder">
                <span style="font-size:40px;">⚓</span>
                <p class="mt-2 small">Select a port on the map to view details, real-time weather & risk profile.</p>
            </div>
            <div id="panelContent" class="d-none">
                <div class="detail-header">
                    <div class="d-flex align-items-center gap-2">
                        <span id="panelFlag"></span>
                        <h5 id="panelPortName" class="mb-0 text-white" style="font-size:16px;font-weight:700;"></h5>
                    </div>
                    <span id="panelPortCode" class="badge bg-dark border border-secondary text-info mt-2" style="font-family:monospace;"></span>
                </div>
                <div class="detail-section">
                    <small class="text-secondary d-block">Operating Status & Type</small>
                    <div class="d-flex justify-content-between mt-1">
                        <span id="panelStatus" class="badge"></span>
                        <span id="panelType" class="text-white small"></span>
                    </div>
                    <hr style="margin:8px 0;border-color:#334155;">
                    <small class="text-secondary">Capacity (TEU/yr)</small>
                    <div id="panelCapacity" class="fw-bold text-white"></div>
                    <small class="text-secondary mt-2 d-block">Annual Trade Volume</small>
                    <div id="panelVolume" class="fw-bold text-white"></div>
                </div>
                <div class="detail-section">
                    <small class="text-secondary d-block">☁ Port Weather</small>
                    <div id="weatherLoader" class="spinner-border spinner-border-sm text-info mt-2" role="status"></div>
                    <div id="weatherContent" class="d-none mt-2">
                        <div class="d-flex justify-content-between text-white small"><span>Temp</span><strong id="weatherTemp"></strong></div>
                        <div class="d-flex justify-content-between text-white small"><span>Wind</span><strong id="weatherWind"></strong></div>
                        <div class="d-flex justify-content-between text-white small"><span>Precip</span><strong id="weatherPrecip"></strong></div>
                        <div class="d-flex justify-content-between text-white small"><span>Humidity</span><strong id="weatherHum"></strong></div>
                        <div class="d-flex justify-content-between text-white small"><span>Visibility</span><strong id="weatherVis"></strong></div>
                    </div>
                </div>
                <div class="detail-section">
                    <small class="text-secondary d-block">🚨 Risk Profile</small>
                    <div class="d-flex justify-content-between align-items-center mt-1"><span>Sourcing Risk</span><span id="panelRisk" class="badge"></span></div>
                    <div class="d-flex justify-content-between align-items-center mt-1"><span>Congestion</span><span id="panelCongestion" class="badge"></span></div>
                    <hr style="margin:8px 0;border-color:#334155;">
                    <small class="text-secondary d-block">Recommendation</small>
                    <p id="panelRecommendation" class="small text-white-50 mb-0 mt-1" style="font-size:11px;line-height:1.4;"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-card mt-4">
    <div class="section-title">📋 Port Directory <span class="badge-pill">{{ $ports->total() }} ports</span></div>
    @if($ports->isEmpty())
        <div class="text-center py-5 text-secondary"><span style="font-size:40px;">⚓</span><p class="mt-2">No ports match your filters.</p></div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg:#0f172a;--bs-table-border-color:#1e293b;--bs-table-hover-bg:#1e293b;">
                <thead>
                    <tr>
                        @foreach(['#','Port Name','Code','Country','Type','Status','Risk','Congestion'] as $h)
                        <th style="font-size:10px;text-transform:uppercase;color:#64748b;padding:10px 12px;background:#020617;">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($ports as $port)
                    @php
                        $sC = match(strtolower($port->status??'')){'active','open'=>'#22c55e','restricted'=>'#f59e0b','closed'=>'#ef4444',default=>'#94a3b8'};
                        $sB = match(strtolower($port->status??'')){'active','open'=>'rgba(34,197,94,.12)','restricted'=>'rgba(245,158,11,.12)','closed'=>'rgba(239,68,68,.12)',default=>'rgba(100,116,139,.12)'};
                        $rC = match($port->risk??''){'High Risk'=>'#ef4444','Medium Risk'=>'#f59e0b',default=>'#22c55e'};
                        $rB = match($port->risk??''){'High Risk'=>'rgba(239,68,68,.12)','Medium Risk'=>'rgba(245,158,11,.12)',default=>'rgba(34,197,94,.12)'};
                        $gC = match($port->congestion??''){'High'=>'#ef4444','Medium'=>'#f59e0b',default=>'#22c55e'};
                        $gB = match($port->congestion??''){'High'=>'rgba(239,68,68,.12)','Medium'=>'rgba(245,158,11,.12)',default=>'rgba(34,197,94,.12)'};
                        $lat = (float)($port->latitude ?: ($port->country?->latitude ?? 0));
                        $lng = (float)($port->longitude ?: ($port->country?->longitude ?? 0));
                    @endphp
                    <tr style="border-bottom:1px solid #1e293b;cursor:pointer;"
                        onclick="window._portTableClick('{{ addslashes($port->country?->name??'') }}',{{ $lat }},{{ $lng }},{{ $port->id }})">
                        <td style="color:#64748b;font-size:12px;padding:10px 12px;">{{ $ports->firstItem()+$loop->index }}</td>
                        <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#f1f5f9;">{{ $port->port_name }}</td>
                        <td style="padding:10px 12px;"><span style="font-size:12px;font-family:monospace;color:#38bdf8;background:rgba(56,189,248,.1);padding:2px 6px;border-radius:4px;">{{ $port->port_code??'—' }}</span></td>
                        <td style="padding:10px 12px;">
                            <div class="d-flex align-items-center gap-2">
                                @if($port->country?->flag)<img src="{{ $port->country->flag }}" width="22" height="15" style="border-radius:2px;object-fit:cover;" onerror="this.style.display='none'">@endif
                                <span style="font-size:12px;color:#94a3b8;">{{ $port->country?->name??'—' }}</span>
                            </div>
                        </td>
                        <td style="padding:10px 12px;color:#94a3b8;font-size:12px;">{{ $port->port_type??'—' }}</td>
                        <td style="padding:10px 12px;"><span style="background:{{ $sB }};color:{{ $sC }};border:1px solid {{ $sC }}33;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">{{ ucfirst($port->status??'Unknown') }}</span></td>
                        <td style="padding:10px 12px;"><span style="background:{{ $rB }};color:{{ $rC }};border:1px solid {{ $rC }}33;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">{{ $port->risk??'Low Risk' }}</span></td>
                        <td style="padding:10px 12px;"><span style="background:{{ $gB }};color:{{ $gC }};border:1px solid {{ $gC }}33;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">{{ $port->congestion??'Low' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($ports->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top:1px solid #1e293b;">
                <div style="font-size:12px;color:#64748b;">Showing {{ $ports->firstItem() }}–{{ $ports->lastItem() }} of {{ $ports->total() }} ports</div>
                {{ $ports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
(function(){
'use strict';
var map,markerLayer,currentPorts=[],markersById={},countryMarker=null;
var countriesList=@json($countries->values()->toArray());
var URL_COUNTRY='{{ route("ports.searchCountry") }}';
var URL_PORT='{{ route("ports.apiData") }}';

window.addEventListener('load',function(){
    console.log('[GERIP] load fired. L=',typeof L!=='undefined'?L.version:'MISSING');
    if(typeof L==='undefined'){document.getElementById('loaderText').textContent='Leaflet failed.';return;}
    map=L.map('portMap',{center:[20,10],zoom:2});
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',{attribution:'&copy; OSM &copy; CARTO',maxZoom:18}).addTo(map);
    L.control.scale({imperial:false}).addTo(map);
    markerLayer=(typeof L.markerClusterGroup==='function')
        ?L.markerClusterGroup({maxClusterRadius:60,showCoverageOnHover:false})
        :L.layerGroup();
    map.addLayer(markerLayer);
    document.getElementById('mapLoader').classList.add('d-none');
    bindAC();
    console.log('[GERIP] Map ready. MarkerCluster=',typeof L.markerClusterGroup==='function');
});

function bindAC(){
    var cI=document.getElementById('countrySearch'),cD=document.getElementById('countryResults');
    var pI=document.getElementById('portSearch'),pD=document.getElementById('portResults');
    cI.addEventListener('input',function(){
        var q=this.value.trim().toLowerCase();
        if(!q){cD.classList.add('d-none');return;}
        var h=countriesList.filter(function(n){return n.toLowerCase().indexOf(q)!==-1;});
        if(!h.length){cD.classList.add('d-none');return;}
        cD.innerHTML=h.slice(0,12).map(function(n){
            return '<div class="autocomplete-item" onclick="window._portSelectCountry(\''+n.replace(/'/g,"\\'")+'\')">'+ n+'</div>';
        }).join('');
        cD.classList.remove('d-none');
    });
    pI.addEventListener('input',function(){
        var q=this.value.trim();
        if(q.length<2){pD.classList.add('d-none');return;}
        fetch(URL_PORT+'?search='+encodeURIComponent(q))
            .then(function(r){return r.json();})
            .then(function(data){
                if(!data||!data.length){pD.classList.add('d-none');return;}
                pD.innerHTML=data.slice(0,10).map(function(p){
                    var cn=(p.country&&p.country.name)?p.country.name.replace(/'/g,"\\'"):'';
                    return '<div class="autocomplete-item" onclick="window._portSelectPort('+p.lat+','+p.lng+','+p.id+',\''+cn+'\')">'
                        +p.name+' ('+(p.code||'—')+')</div>';
                }).join('');
                pD.classList.remove('d-none');
            }).catch(function(e){console.error('[GERIP] port search:',e);});
    });
    document.addEventListener('click',function(e){
        if(!e.target.closest('.autocomplete-wrapper')){cD.classList.add('d-none');pD.classList.add('d-none');}
    });
}

window._portSelectCountry=function(name){
    document.getElementById('countrySearch').value=name;
    document.getElementById('countryResults').classList.add('d-none');
    loader('Loading '+name+'...');
    var url=URL_COUNTRY+'?country_name='+encodeURIComponent(name);
    console.log('[GERIP] GET',url);
    fetch(url)
        .then(function(r){console.log('[GERIP] HTTP',r.status);if(!r.ok)throw new Error('HTTP '+r.status);return r.json();})
        .then(function(data){
            unloader();
            console.log('[GERIP] Response:',JSON.stringify(data).slice(0,200));
            if(!data||!data.country){console.warn('[GERIP] No country field.');return;}
            render(data);
        })
        .catch(function(e){unloader();console.error('[GERIP] Error:',e);});
};

function render(data){
    markerLayer.clearLayers();markersById={};countryMarker=null;
    currentPorts=data.ports||[];
    var c=data.country;
    var clat = parseFloat(c.latitude);
    var clng = parseFloat(c.longitude);
    var hasCountryCoords = clat && clng && !isNaN(clat) && !isNaN(clng) && clat !== 0 && clng !== 0;

    if(hasCountryCoords){
        var ci=L.divIcon({html:'<div style="font-size:32px;line-height:1;cursor:pointer;filter:drop-shadow(0 2px 6px rgba(0,0,0,.7))">📍</div>',className:'',iconSize:[36,36],iconAnchor:[18,34]});
        var ch='<div style="min-width:220px;font-family:Inter,sans-serif;color:#f1f5f9;background:#0f172a;padding:10px;border-radius:8px">'
            +'<strong style="font-size:14px;color:#fff">📍 '+c.name+'</strong>'
            +'<hr style="border-color:#334155;margin:6px 0">'
            +'<div style="font-size:11px;line-height:1.9">'
            +'<b>Region:</b> '+c.region+'<br><b>Currency:</b> '+c.currency
            +'<br><b>GDP:</b> '+c.gdp+'<br><b>Population:</b> '+c.population
            +'<br><b>Risk:</b> '+c.risk+'</div></div>';
        countryMarker=L.marker([clat,clng],{icon:ci}).bindPopup(ch,{maxWidth:260,closeButton:false});
        markerLayer.addLayer(countryMarker);
    }
    currentPorts.forEach(function(p){
        var lat = parseFloat(p.latitude) || parseFloat(p.lat);
        var lng = parseFloat(p.longitude) || parseFloat(p.lng);
        if(!lat || !lng || isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0){
            if(hasCountryCoords){
                lat = clat;
                lng = clng;
            } else {
                return;
            }
        }
        p.latitude = lat;
        p.longitude = lng;
        var risk=p.risk_score||p.risk||'Low Risk';
        var rc=risk==='High Risk'?'#ef4444':(risk==='Medium Risk'?'#f59e0b':'#22c55e');
        var pi=L.divIcon({html:'<div style="font-size:20px;line-height:1;cursor:pointer;filter:drop-shadow(0 1px 4px rgba(0,0,0,.7))">⚓</div>',className:'',iconSize:[24,24],iconAnchor:[12,12]});
        var ph='<div style="min-width:220px;font-family:Inter,sans-serif;color:#f1f5f9;background:#0f172a;padding:10px;border-radius:8px">'
            +'<strong style="font-size:13px;color:#fff">⚓ '+p.name+'</strong>'
            +'<br><span style="font-size:10px;font-family:monospace;color:#38bdf8;background:rgba(56,189,248,.1);padding:1px 5px;border-radius:3px">'+(p.code||'—')+'</span>'
            +'<hr style="border-color:#334155;margin:6px 0">'
            +'<div style="font-size:11px;line-height:1.9">'
            +'<b>Type:</b> '+(p.type||p.port_type||'—')+'<br><b>Status:</b> '+(p.status||'—')
            +'<br><b>Capacity:</b> '+(p.capacity?Number(p.capacity).toLocaleString():'—')
            +'<br><b>Congestion:</b> '+(p.congestion||'—')
            +'<br><b>Risk:</b> <span style="color:'+rc+';font-weight:700">'+risk+'</span>'
            +'</div>'
            +'<button class="btn btn-primary btn-sm w-100 mt-2" style="font-size:10px" onclick="window._portShowPanel('+p.id+')">View Full Profile</button>'
            +'</div>';
        var m=L.marker([lat,lng],{icon:pi}).bindPopup(ph,{maxWidth:260,closeButton:false});
        m.on('click',function(){window._portShowPanel(p.id);});
        markerLayer.addLayer(m);
        markersById[p.id]=m;
    });
    if(hasCountryCoords){
        map.setView([clat, clng], 6);
        setTimeout(function(){if(countryMarker)countryMarker.openPopup();}, 500);
    }
    console.log('[GERIP] Rendered',currentPorts.length,'ports for',c.name);
}

window._portSelectPort=function(lat,lng,id,cn){
    document.getElementById('portResults').classList.add('d-none');
    if(currentPorts.some(function(p){return p.id===id;})){_locate(lat,lng,id);}
    else{window._portSelectCountry(cn);setTimeout(function(){_locate(lat,lng,id);},2400);}
};

window._portTableClick=function(cn,lat,lng,id){
    if(currentPorts.some(function(p){return p.id===id;})){_locate(lat,lng,id);}
    else{window._portSelectCountry(cn);setTimeout(function(){_locate(lat,lng,id);},2400);}
};

function _locate(lat,lng,id){
    map.flyTo([lat,lng],10,{duration:1.2});
    setTimeout(function(){var m=markersById[id];if(m)m.openPopup();window._portShowPanel(id);},1400);
}

window._portShowPanel=function(id){
    var p=currentPorts.find(function(x){return x.id===id;});
    if(!p){console.warn('[GERIP] Port',id,'not found');return;}
    document.getElementById('panelPlaceholder').classList.add('d-none');
    document.getElementById('panelContent').classList.remove('d-none');
    document.getElementById('panelPortName').textContent=p.name;
    document.getElementById('panelPortCode').textContent=p.code||'—';
    document.getElementById('panelType').textContent=p.type||'General Cargo';
    document.getElementById('panelCapacity').textContent=p.capacity?Number(p.capacity).toLocaleString():'N/A';
    document.getElementById('panelVolume').textContent=p.trade_volume?Number(p.trade_volume).toLocaleString():'N/A';
    document.getElementById('panelRecommendation').textContent='Establish alternative routing via secondary terminals to hedge congestion risks.';
    var s=document.getElementById('panelStatus');
    s.textContent=p.status||'Active';
    s.className='badge '+((p.status==='Open'||p.status==='Active')?'bg-success':p.status==='Restricted'?'bg-warning':'bg-danger');
    var r=document.getElementById('panelRisk');
    r.textContent=p.risk_score||'Low Risk';
    r.className='badge '+(p.risk_score==='High Risk'?'bg-danger':p.risk_score==='Medium Risk'?'bg-warning':'bg-success');
    var g=document.getElementById('panelCongestion');
    g.textContent=p.congestion||'Low';
    g.className='badge '+(p.congestion==='High'?'bg-danger':p.congestion==='Medium'?'bg-warning':'bg-success');
    var wL=document.getElementById('weatherLoader'),wC=document.getElementById('weatherContent');
    wL.classList.remove('d-none');wC.classList.add('d-none');
    fetch('https://api.open-meteo.com/v1/forecast?latitude='+p.latitude+'&longitude='+p.longitude+'&current=temperature_2m,wind_speed_10m,relative_humidity_2m,precipitation,visibility&timezone=auto')
        .then(function(r){return r.json();})
        .then(function(w){
            var c=w.current||{};
            document.getElementById('weatherTemp').textContent=c.temperature_2m!=null?c.temperature_2m+' °C':'N/A';
            document.getElementById('weatherWind').textContent=c.wind_speed_10m!=null?c.wind_speed_10m+' km/h':'N/A';
            document.getElementById('weatherPrecip').textContent=c.precipitation!=null?c.precipitation+' mm':'N/A';
            document.getElementById('weatherHum').textContent=c.relative_humidity_2m!=null?c.relative_humidity_2m+' %':'N/A';
            document.getElementById('weatherVis').textContent=c.visibility!=null?(c.visibility/1000).toFixed(1)+' km':'N/A';
            wL.classList.add('d-none');wC.classList.remove('d-none');
        }).catch(function(){document.getElementById('weatherTemp').textContent='API Offline';wL.classList.add('d-none');wC.classList.remove('d-none');});
};

function loader(t){document.getElementById('loaderText').textContent=t||'Loading...';document.getElementById('mapLoader').classList.remove('d-none');}
function unloader(){document.getElementById('mapLoader').classList.add('d-none');}
}());
</script>
@endpush

</x-dashboard-layout>
