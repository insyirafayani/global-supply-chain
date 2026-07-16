@php
    $weather = $country->weatherData->last();
@endphp

<div class="card card-dark dashboard-card p-4">
    <h4 class="mb-4 text-info">☁ Live Weather Status</h4>
    
    @if($weather)
        <div class="row g-4 mb-4">
            <!-- Temperature -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Temperature</small>
                    <h3 class="mb-0 text-white">
                        {{ $weather->temperature !== null ? $weather->temperature . '°C' : '-' }}
                    </h3>
                    <small class="text-secondary d-block mt-2">Current Reading</small>
                </div>
            </div>

            <!-- Rainfall -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Precipitation / Rainfall</small>
                    <h3 class="mb-0 text-info">
                        {{ $weather->rainfall !== null ? $weather->rainfall . ' mm' : '-' }}
                    </h3>
                    <small class="text-secondary d-block mt-2">Hourly Rain Accumulation</small>
                </div>
            </div>

            <!-- Wind Speed -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Wind Speed</small>
                    <h3 class="mb-0 text-warning">
                        {{ $weather->wind_speed !== null ? $weather->wind_speed . ' km/h' : '-' }}
                    </h3>
                    <small class="text-secondary d-block mt-2">Wind Velocity</small>
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Weather Status</small>
                    <h3 class="mb-0 {{ $weather->weather_status === 'Extreme' ? 'text-danger' : ($weather->weather_status === 'Storm Risk' ? 'text-warning' : 'text-success') }}">
                        {{ $weather->weather_status ?? 'Normal' }}
                    </h3>
                    <small class="text-secondary d-block mt-2">Risk Rating</small>
                </div>
            </div>
        </div>

        <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background: rgba(15, 23, 42, 0.6); border: 1px solid #1e293b;">
            <span style="font-size:12px; color:#64748b;">Data Source: Open-Meteo API</span>
            <span style="font-size:12px; color:#64748b;">Last Updated: {{ $weather->updated_at ? $weather->updated_at->format('Y-m-d H:i:s') : '-' }}</span>
        </div>
    @else
        <div class="text-center py-5 text-secondary">
            <span style="font-size: 40px;">📭</span>
            <p class="mt-2">No weather data available for this country.</p>
        </div>
    @endif
</div>
