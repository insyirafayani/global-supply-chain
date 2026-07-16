<x-dashboard-layout>
@section('title', 'Executive Dashboard')

<style>
/* ===== ENTERPRISE THEME VARIABLES ===== */
:root {
    --ent-bg: #0B1220;
    --ent-card: #131C2E;
    --ent-border: rgba(255,255,255,0.08);
    --ent-primary: #2F80ED;
    --ent-success: #27AE60;
    --ent-warning: #F2C94C;
    --ent-danger: #EB5757;
}

body {
    background-color: var(--ent-bg) !important;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideRight { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
@keyframes fade { from { opacity: 0; } to { opacity: 1; } }

.anim-fade-down { animation: fadeDown 0.35s ease both; }
.anim-fade-up { animation: fadeUp 0.35s ease both; animation-delay: 0.1s; }
.anim-fade-up-2 { animation: fadeUp 0.35s ease both; animation-delay: 0.2s; }
.anim-slide-right { animation: slideRight 0.35s ease both; animation-delay: 0.3s; }
.anim-fade { animation: fade 0.35s ease both; animation-delay: 0.15s; }

/* ===== ENTERPRISE CARDS ===== */
.ent-card {
    background: var(--ent-card);
    border: 1px solid var(--ent-border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}
.ent-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.4), 0 0 15px rgba(47, 128, 237, 0.15);
    border-color: rgba(47, 128, 237, 0.3);
}

.kpi-title { font-size: 14px; color: #94A3B8; font-weight: 500; }
.kpi-value { font-size: 36px; color: #FFFFFF; font-weight: 700; letter-spacing: 0.5px; line-height: 1.2; }
.kpi-status { font-size: 12px; font-weight: 600; }
.kpi-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
}

/* ===== SPARKLINE ===== */
.sparkline-box {
    margin-top: 16px;
    height: 40px;
    width: 100%;
}

/* ===== HEADERS & TYPOGRAPHY ===== */
.section-title-wrap {
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 12px;
}
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #F8FAFC;
    margin: 0;
    display: flex; align-items: center; gap: 8px;
}
.section-title-gradient {
    position: absolute;
    bottom: 0; left: 0;
    width: 60px; height: 3px;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--ent-primary), transparent);
}

/* ===== MAP CONTAINER ===== */
.map-container {
    background: var(--ent-card);
    border: 1px solid var(--ent-border);
    border-radius: 16px;
    padding: 8px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    position: relative;
}
#globalMap { height: 500px; border-radius: 12px; z-index: 1; }

.search-box-wrapper {
    position: absolute; top: 20px; left: 20px; z-index: 1000; width: 340px;
}
.search-input {
    background: rgba(11, 18, 32, 0.8) !important;
    backdrop-filter: blur(8px);
    border: 1px solid var(--ent-primary) !important;
    color: #f8fafc !important;
    border-radius: 12px;
    padding: 14px 20px;
    font-size: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5), 0 0 15px rgba(47, 128, 237, 0.3);
    width: 100%;
}
.search-input:focus { outline: none; box-shadow: 0 10px 25px rgba(0,0,0,0.6), 0 0 25px rgba(47, 128, 237, 0.5); }
.autocomplete-results {
    background: rgba(19, 28, 46, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid var(--ent-border);
    border-radius: 12px; margin-top: 6px;
    max-height: 250px; overflow-y: auto;
    box-shadow: 0 10px 35px rgba(0,0,0,0.6); display: none;
}
.autocomplete-item {
    padding: 12px 20px; color: #CBD5E1; cursor: pointer; font-size: 13px; font-weight: 500;
    border-bottom: 1px solid var(--ent-border); transition: all 0.2s;
}
.autocomplete-item:last-child { border-bottom: none; }
.autocomplete-item:hover { background: rgba(47, 128, 237, 0.15); color: #fff; }

/* ===== NEWS FEED ===== */
.news-feed-item {
    padding: 16px;
    border-radius: 12px;
    border: 1px solid transparent;
    background: rgba(255,255,255,0.02);
    margin-bottom: 12px;
    transition: all 0.2s ease;
}
.news-feed-item:hover {
    background: rgba(47, 128, 237, 0.05);
    border-color: rgba(47, 128, 237, 0.2);
    transform: translateX(4px);
}
.news-title {
    font-size: 14px; font-weight: 600; color: #F1F5F9;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    margin: 8px 0; text-decoration: none;
}
.news-title:hover { color: var(--ent-primary); }

/* ===== DECORATIONS ===== */
.bg-glow {
    position: absolute; top: -100px; right: -100px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(47,128,237,0.15) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none; z-index: 0;
}
</style>

<div class="container-fluid py-4 position-relative">
    <div class="bg-glow"></div>
    
    {{-- 1. ENTERPRISE HEADER --}}
    <div class="row mb-4 align-items-center anim-fade-down">
        <div class="col-lg-8">
            <h2 class="fw-bold text-white mb-2" style="font-size: 28px;">
                <span style="font-size: 32px;">🌐</span> Welcome back, <span style="color: var(--ent-primary);">{{ auth()->user()->name }}</span>
            </h2>
            <p class="text-secondary mb-0" style="font-size: 15px;">
                Global Export Risk Intelligence Platform &bull; Monitor global trade risks and make smarter decisions.
            </p>
        </div>
        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="ent-card p-3" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-white fw-bold" style="font-size: 13px;">Live Intelligence Sync</span>
                    <span class="text-secondary" style="font-size: 11px;">{{ now()->format('d M Y, H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2" style="font-size: 11px; font-weight: 600;">
                    <span class="text-success"><i class="fas fa-check-circle"></i> API</span>
                    <span class="text-success"><i class="fas fa-check-circle"></i> Weather</span>
                    <span class="text-success"><i class="fas fa-check-circle"></i> Risk</span>
                    <span class="text-success"><i class="fas fa-check-circle"></i> News</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. 4 KPI CARDS --}}
    <div class="row g-4 mb-4 anim-fade-up">
        <!-- Total Countries -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="ent-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">Global Countries</div>
                        <div class="kpi-value text-white">{{ $totalCountries }}</div>
                    </div>
                    <div class="kpi-icon" style="background: rgba(47, 128, 237, 0.1); color: var(--ent-primary);">
                        🌍
                    </div>
                </div>
                <div class="kpi-status text-success mt-2">↑ +2 synced this week</div>
                <!-- Dummy Sparkline -->
                <div class="sparkline-box">
                    <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                        <polyline fill="none" stroke="var(--ent-primary)" stroke-width="2" points="0,20 20,15 40,25 60,10 80,15 100,5"/>
                        <polyline fill="rgba(47,128,237,0.1)" stroke="none" points="0,30 0,20 20,15 40,25 60,10 80,15 100,5 100,30"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- High Risk -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="ent-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">High Risk Zones</div>
                        <div class="kpi-value" style="color: var(--ent-danger);">{{ $highRiskCount }}</div>
                    </div>
                    <div class="kpi-icon" style="background: rgba(235, 87, 87, 0.1); color: var(--ent-danger);">
                        🔴
                    </div>
                </div>
                <div class="kpi-status text-danger mt-2">↓ Critical alerts active</div>
                <div class="sparkline-box">
                    <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                        <polyline fill="none" stroke="var(--ent-danger)" stroke-width="2" points="0,25 20,20 40,22 60,15 80,18 100,10"/>
                        <polyline fill="rgba(235,87,87,0.1)" stroke="none" points="0,30 0,25 20,20 40,22 60,15 80,18 100,10 100,30"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Medium Risk -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="ent-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">Medium Risk</div>
                        <div class="kpi-value" style="color: var(--ent-warning);">{{ $mediumRiskCount }}</div>
                    </div>
                    <div class="kpi-icon" style="background: rgba(242, 201, 76, 0.1); color: var(--ent-warning);">
                        🟡
                    </div>
                </div>
                <div class="kpi-status text-warning mt-2">− Stable surveillance</div>
                <div class="sparkline-box">
                    <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                        <polyline fill="none" stroke="var(--ent-warning)" stroke-width="2" points="0,15 20,18 40,12 60,14 80,10 100,15"/>
                        <polyline fill="rgba(242,201,76,0.1)" stroke="none" points="0,30 0,15 20,18 40,12 60,14 80,10 100,15 100,30"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Risk -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="ent-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">Low Risk</div>
                        <div class="kpi-value" style="color: var(--ent-success);">{{ $lowRiskCount }}</div>
                    </div>
                    <div class="kpi-icon" style="background: rgba(39, 174, 96, 0.1); color: var(--ent-success);">
                        🟢
                    </div>
                </div>
                <div class="kpi-status text-success mt-2">↑ Safe Trade Corridors</div>
                <div class="sparkline-box">
                    <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                        <polyline fill="none" stroke="var(--ent-success)" stroke-width="2" points="0,10 20,12 40,8 60,15 80,5 100,12"/>
                        <polyline fill="rgba(39,174,96,0.1)" stroke="none" points="0,30 0,10 20,12 40,8 60,15 80,5 100,12 100,30"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. WORLD RISK MAP --}}
    <div class="row mb-4 anim-fade">
        <div class="col-12">
            <div class="section-title-wrap">
                <h4 class="section-title">🗺️ Global Trade Risk Map</h4>
                <div class="section-title-gradient"></div>
            </div>
            <div class="map-container">
                <div class="search-box-wrapper">
                    <input type="text" id="countrySearch" class="search-input" placeholder="🔍 Search Country Intelligence (e.g. Indonesia, Germany)...">
                    <div id="searchResults" class="autocomplete-results"></div>
                </div>
                <div id="globalMap"></div>
            </div>
        </div>
    </div>

    {{-- 4. INTELLIGENCE & NEWS --}}
    <div class="row g-4 anim-fade-up-2">
        
        {{-- SELECTED COUNTRY --}}
        <div class="col-xl-7 col-lg-7">
            <div class="section-title-wrap">
                <h4 class="section-title">📈 Selected Country Intelligence</h4>
                <div class="section-title-gradient"></div>
            </div>
            
            <div class="ent-card h-100">
                <div id="noSelectionContainer" class="text-center py-5">
                    <span style="font-size: 54px; opacity: 0.1;">🌐</span>
                    <p class="text-secondary mt-3 fw-medium">Select a country on the map or use the search box to view deep intelligence analytics.</p>
                </div>
                
                <div id="countryDetailsContainer" style="display:none;">
                    <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3" style="border-color: var(--ent-border) !important;">
                        <img id="detailFlag" src="" width="70" style="border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                        <div>
                            <h3 class="fw-bold mb-0 text-white" id="detailName">Country Name</h3>
                            <span class="text-secondary" style="font-size: 13px;" id="detailCapital">Capital</span>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="badge px-3 py-2" id="detailRiskLevel" style="font-size: 12px;">Risk Level</span>
                            <div class="text-white fw-bold mt-1 fs-5" id="detailRiskScore">Score</div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <h6 class="text-secondary mb-3 fw-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Economic Indicators</h6>
                            <table class="table table-borderless text-white mb-0" style="font-size: 14px;">
                                <tr><td class="text-secondary py-2 px-0">Region</td><td class="text-end fw-semibold py-2 px-0" id="detailRegion">—</td></tr>
                                <tr><td class="text-secondary py-2 px-0">GDP</td><td class="text-end fw-semibold py-2 px-0" id="detailGDP">—</td></tr>
                                <tr><td class="text-secondary py-2 px-0">Population</td><td class="text-end fw-semibold py-2 px-0" id="detailPopulation">—</td></tr>
                                <tr><td class="text-secondary py-2 px-0">Inflation</td><td class="text-end fw-semibold py-2 px-0" id="detailInflation">—</td></tr>
                                <tr><td class="text-secondary py-2 px-0">Currency</td><td class="text-end fw-semibold py-2 px-0" id="detailCurrency">—</td></tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-secondary mb-3 fw-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Logistics & Environment</h6>
                            <div class="p-3 rounded-3 mb-3" style="background: rgba(0,0,0,0.2); border: 1px solid var(--ent-border);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary" style="font-size: 13px;">Weather</span>
                                    <span class="badge bg-secondary text-white" id="detailWeatherStatus">—</span>
                                </div>
                                <div class="fs-4 text-info fw-bold mt-1" id="detailTemp">—</div>
                            </div>
                            
                            <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2); border: 1px solid var(--ent-border);">
                                <div class="text-secondary mb-1" style="font-size: 13px;">AI Recommendation</div>
                                <div class="text-white" style="font-size: 12.5px; line-height: 1.5;" id="detailRecommendation">—</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-secondary text-end mb-3" style="font-size: 11px;" id="detailLastUpdated">Last Updated: Data not available</div>
                    
                    <a id="intelligenceBtn" href="#" class="btn w-100" style="background: var(--ent-primary); color: #fff; border-radius: 10px; padding: 12px; font-weight: 600; box-shadow: 0 4px 15px rgba(47,128,237,0.3);">
                        Open Country Intelligence Center &rarr;
                    </a>
                </div>
            </div>
        </div>

        {{-- LATEST NEWS --}}
        <div class="col-xl-5 col-lg-5 anim-slide-right">
            <div class="section-title-wrap">
                <h4 class="section-title">📰 Global Trade Incidents</h4>
                <div class="section-title-gradient"></div>
            </div>
            
            <div class="ent-card h-100 p-0" style="background: transparent; border: none; box-shadow: none;">
                @forelse($latestNews as $news)
                    <div class="news-feed-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge px-2 py-1" style="font-size: 10px; 
                                @if($news->sentiment=='Negative') background: rgba(235,87,87,0.15); color: #EB5757; border: 1px solid rgba(235,87,87,0.3);
                                @elseif($news->sentiment=='Positive') background: rgba(39,174,96,0.15); color: #27AE60; border: 1px solid rgba(39,174,96,0.3);
                                @else background: rgba(255,255,255,0.1); color: #CBD5E1; border: 1px solid rgba(255,255,255,0.2); @endif">
                                {{ $news->sentiment }}
                            </span>
                            <span class="text-secondary" style="font-size: 11px;"><i class="far fa-clock"></i> {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->diffForHumans() : $news->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ $news->url }}" target="_blank" class="news-title">{{ $news->title }}</a>
                        <div class="text-secondary mt-2" style="font-size: 11.5px;">
                            <i class="far fa-newspaper me-1"></i> Source: <span class="text-white">{{ $news->source ?? 'Global Feed' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 ent-card">
                        <p class="text-secondary">No recent trade incidents recorded.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>

</div>

{{-- ============================================================
     LEAFLET WORLD MAP SCRIPTS (LOGIC UNTOUCHED)
     ============================================================ --}}
<script>
    var dbCountries = @json($mapCountries);

    var map = null;
    var countryMarkers = [];
    var portMarkers = [];

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Map
        map = L.map('globalMap', {
            center: [20, 0],
            zoom: 2,
            zoomControl: true
        });

        // Add Dark Map Tile (CARTO Dark Matter for Enterprise look)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            maxZoom: 18
        }).addTo(map);

        // Load country markers
        dbCountries.forEach(function (country) {
            if (!country.lat || !country.lng) return;

            var riskColor = '#27AE60'; // default Low Risk
            if (country.risk_level === 'High Risk') riskColor = '#EB5757';
            if (country.risk_level === 'Medium Risk') riskColor = '#F2C94C';

            var marker = L.circleMarker([country.lat, country.lng], {
                radius: 8,
                color: riskColor,
                fillColor: riskColor,
                fillOpacity: 0.8,
                weight: 2
            });

            marker.bindPopup(buildCountryPopupHtml(country), { maxWidth: 285 });
            marker.addTo(map);

            // Click listener
            marker.on('click', function () {
                showCountryDetails(country);
                showPortMarkers(country);
            });

            countryMarkers.push({
                countryId: country.id,
                name: country.name.toLowerCase(),
                marker: marker,
                data: country
            });
        });

        // Autocomplete Search logic
        var searchInput = document.getElementById('countrySearch');
        var searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function () {
            var val = this.value.toLowerCase().trim();
            searchResults.innerHTML = '';
            
            if (!val) {
                searchResults.style.display = 'none';
                return;
            }

            var filtered = dbCountries.filter(function (c) {
                return c.name.toLowerCase().indexOf(val) !== -1;
            }).slice(0, 8);

            if (filtered.length === 0) {
                searchResults.style.display = 'none';
                return;
            }

            filtered.forEach(function (c) {
                var div = document.createElement('div');
                div.className = 'autocomplete-item';
                div.innerHTML = '🌍 ' + c.name + ' (' + c.region + ')';
                div.addEventListener('click', function () {
                    selectCountry(c);
                });
                searchResults.appendChild(div);
            });

            searchResults.style.display = 'block';
        });

        // Hide search results on click outside
        document.addEventListener('click', function (e) {
            if (e.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });
    });

    // Handle country selection
    function selectCountry(country) {
        document.getElementById('countrySearch').value = country.name;
        document.getElementById('searchResults').style.display = 'none';

        // Find country marker
        var found = countryMarkers.find(function (m) {
            return m.countryId === country.id;
        });

        if (found) {
            map.setView([country.lat, country.lng], 5);
            found.marker.openPopup();
            showCountryDetails(country);
            showPortMarkers(country);
        }
    }

    // Display ports markers for selected country
    function showPortMarkers(country) {
        // Clear previous port markers
        portMarkers.forEach(function (m) {
            map.removeLayer(m);
        });
        portMarkers = [];

        if (!country.ports || country.ports.length === 0) return;

        country.ports.forEach(function (port) {
            if (!port.lat || !port.lng) return;

            // Anchor/blue circle marker for ports
            var marker = L.circleMarker([port.lat, port.lng], {
                radius: 6,
                color: '#38bdf8',
                fillColor: '#0369a1',
                fillOpacity: 0.9,
                weight: 1.5
            });

            var popupContent = '<div style="color:#0f172a; font-family:sans-serif; min-width:180px;">'
                + '⚓ <b>' + port.name + '</b> (' + (port.code || 'N/A') + ')<br>'
                + '<hr style="margin:5px 0;">'
                + '<b>Location:</b> ' + (port.location || '—') + '<br>'
                + '<b>Type:</b> ' + port.port_type + '<br>'
                + '<b>Capacity:</b> ' + port.capacity + '<br>'
                + '<b>Status:</b> <span style="color:' + (port.status === 'Open' ? '#27AE60' : '#EB5757') + '; font-weight:700;">' + port.status + '</span><br>'
                + '<b>Congestion:</b> ' + port.congestion + '<br>'
                + '<b>Risk:</b> ' + port.risk
                + '</div>';

            marker.bindPopup(popupContent).addTo(map);
            portMarkers.push(marker);
        });
    }

    // Generate country popup html
    function buildCountryPopupHtml(c) {
        var riskColor = '#27AE60';
        if (c.risk_level === 'High Risk') riskColor = '#EB5757';
        if (c.risk_level === 'Medium Risk') riskColor = '#F2C94C';

        var flagHtml = c.flag ? '<img src="' + c.flag + '" width="24" height="16" style="border-radius:2px; vertical-align:middle; margin-right:6px;">' : '';
        
        return '<div style="color:#0f172a; font-family:Inter,sans-serif; min-width:240px;">'
            + '<div style="display:flex;align-items:center;margin-bottom:8px;">'
            + flagHtml
            + '<strong style="font-size:14px;">' + c.name + '</strong>'
            + '</div>'
            + '<table style="width:100%; font-size:11px; margin-bottom:8px;">'
            + '<tr><td style="color:#64748b;">Capital:</td><td><b>' + (c.capital || '—') + '</b></td></tr>'
            + '<tr><td style="color:#64748b;">GDP:</td><td><b>' + (c.gdp ? '$' + (c.gdp / 1e9).toFixed(1) + 'B' : '—') + '</b></td></tr>'
            + '<tr><td style="color:#64748b;">Risk Score:</td><td><strong style="color:' + riskColor + ';">' + (c.risk_score || '—') + ' (' + c.risk_level + ')</strong></td></tr>'
            + '</table>'
            + '<a href="/countries/' + c.id + '" style="display:block; text-align:center; background:#2F80ED; color:#fff; padding:6px; border-radius:6px; text-decoration:none; font-size:11px; font-weight:600;">'
            + '🚀 Open Country Intelligence'
            + '</a>'
            + '</div>';
    }

    // Update Bottom Info Card
    function showCountryDetails(c) {
        document.getElementById('noSelectionContainer').style.display = 'none';
        document.getElementById('countryDetailsContainer').style.display = 'block';

        if (document.getElementById('selectedCountryName')) {
            document.getElementById('selectedCountryName').textContent = c.name + ' (Loading...)';
        }
        document.getElementById('detailName').textContent = c.name + ' (Loading...)';
        
        fetch('/api/dashboard/country/' + c.id)
            .then(response => response.json())
            .then(data => {
                var latest = data;
                
                if (document.getElementById('selectedCountryName')) {
                    document.getElementById('selectedCountryName').textContent = latest.name || 'Data not available';
                }
                
                document.getElementById('detailName').textContent = latest.name || 'Data not available';
                document.getElementById('detailCapital').textContent = latest.capital ? 'Capital: ' + latest.capital : 'Capital: Data not available';
                document.getElementById('detailFlag').src = latest.flag || '';
                document.getElementById('detailRegion').textContent = latest.region || 'Data not available';
                
                document.getElementById('detailGDP').textContent = latest.gdp ? '$' + (latest.gdp / 1e9).toFixed(1) + 'B' : 'Data not available';
                document.getElementById('detailPopulation').textContent = latest.population ? latest.population.toLocaleString() : 'Data not available';
                document.getElementById('detailInflation').textContent = latest.inflation !== null ? latest.inflation + '%' : 'Data not available';
                document.getElementById('detailCurrency').textContent = latest.currency || 'Data not available';
                
                document.getElementById('detailTemp').textContent = latest.temperature !== null ? latest.temperature + '°C' : 'Data not available';
                
                var weatherText = latest.weather_status;
                if (!weatherText) weatherText = 'Data not available';
                document.getElementById('detailWeatherStatus').textContent = weatherText;
                
                document.getElementById('detailRiskScore').textContent = latest.risk_score !== null ? latest.risk_score + '/100' : 'Data not available';
                
                var riskLevelEl = document.getElementById('detailRiskLevel');
                riskLevelEl.textContent = latest.risk_level || 'Data not available';
                
                var bgClass = 'bg-success';
                if (latest.risk_level === 'High Risk') bgClass = 'bg-danger';
                else if (latest.risk_level === 'Medium Risk') bgClass = 'bg-warning text-dark';
                else if (!latest.risk_level) bgClass = 'bg-secondary';
                
                riskLevelEl.className = 'badge ' + bgClass;

                document.getElementById('detailRecommendation').textContent = latest.recommendation ? '💡 ' + latest.recommendation : 'Data not available';
                
                if (document.getElementById('detailLastUpdated')) {
                    document.getElementById('detailLastUpdated').textContent = 'Last Updated: ' + (latest.last_updated || 'Unknown');
                }
                
                document.getElementById('intelligenceBtn').href = '/countries/' + latest.id;
            })
            .catch(error => {
                console.error('Error fetching real-time country data:', error);
                document.getElementById('detailName').textContent = c.name + ' (Error loading data)';
            });
    }
</script>
</x-dashboard-layout>