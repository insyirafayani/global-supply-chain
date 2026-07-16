@php
    $currency = $country->currencyRates->last();
@endphp

<div class="card card-dark dashboard-card p-4">
    <h4 class="mb-4 text-info">💱 Currency Intelligence</h4>
    
    @if($currency)
        <div class="row g-4 mb-4">
            <!-- Currency Name/Code -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Currency Code / Name</small>
                    <h3 class="mb-0 text-white">
                        {{ $currency->currency_code }}
                    </h3>
                    <small class="text-secondary d-block mt-2">{{ $currency->currency_name ?? 'Local Currency' }}</small>
                </div>
            </div>

            <!-- Exchange Rate -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Exchange Rate (vs USD)</small>
                    <h3 class="mb-0 text-white">
                        {{ number_format($currency->exchange_rate, 4) }}
                    </h3>
                    <small class="text-secondary d-block mt-2">1 USD = {{ $currency->exchange_rate }} {{ $currency->currency_code }}</small>
                </div>
            </div>

            <!-- Volatility Change -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">24h Price Change</small>
                    <h3 class="mb-0 {{ $currency->change_percent > 0 ? 'text-success' : ($currency->change_percent < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ $currency->change_percent > 0 ? '+' : '' }}{{ number_format($currency->change_percent, 2) }}%
                    </h3>
                    <small class="text-secondary d-block mt-2">vs USD Base Rate</small>
                </div>
            </div>

            <!-- Volatility Risk Status -->
            <div class="col-md-6 col-lg-3">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Volatility Status</small>
                    <h3 class="mb-0 @if($currency->currency_status === 'Trade Critical') text-danger @elseif($currency->currency_status === 'Cost Surge') text-warning @elseif($currency->currency_status === 'Cost Warning') text-warning-50 @else text-success @endif">
                        {{ $currency->currency_status ?? 'Cost Stable' }}
                    </h3>
                    <small class="text-secondary d-block mt-2">Import Cost Impact</small>
                </div>
            </div>
        </div>

        <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background: rgba(15, 23, 42, 0.6); border: 1px solid #1e293b;">
            <span style="font-size:12px; color:#64748b;">Data Source: Exchange Rates API (USD Base)</span>
            <span style="font-size:12px; color:#64748b;">Last Updated: {{ $currency->updated_at ? $currency->updated_at->format('Y-m-d H:i:s') : '-' }}</span>
        </div>
    @else
        <div class="text-center py-5 text-secondary">
            <span style="font-size: 40px;">📭</span>
            <p class="mt-2">No currency data available for this country.</p>
        </div>
    @endif
</div>
