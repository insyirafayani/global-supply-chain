@php
    $economicRecords = $country->economicData->sortBy('year');
    $economic = $economicRecords->last();
    $prevEconomic = $economicRecords->count() > 1 ? $economicRecords->values()->get($economicRecords->count() - 2) : null;
    
    $gdpGrowth = null;
    if ($economic && $prevEconomic && $prevEconomic->gdp > 0) {
        $gdpGrowth = (($economic->gdp - $prevEconomic->gdp) / $prevEconomic->gdp) * 100;
    }
@endphp

<div class="card card-dark dashboard-card p-4">
    <h4 class="mb-4 text-info">📊 Economic Indicators (Year: {{ $economic->year ?? '-' }})</h4>
    
    @if($economic)
        <div class="row g-4 mb-4">
            <!-- GDP -->
            <div class="col-md-6 col-lg-4">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Gross Domestic Product (GDP)</small>
                    <h3 class="mb-0 text-white">
                        @if($economic->gdp >= 1e12)
                            ${{ number_format($economic->gdp / 1e12, 2) }} Trillion
                        @elseif($economic->gdp >= 1e9)
                            ${{ number_format($economic->gdp / 1e9, 2) }} Billion
                        @elseif($economic->gdp >= 1e6)
                            ${{ number_format($economic->gdp / 1e6, 2) }} Million
                        @else
                            ${{ number_format($economic->gdp, 2) }}
                        @endif
                    </h3>
                    @if($gdpGrowth !== null)
                        <small class="d-block mt-2 {{ $gdpGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $gdpGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($gdpGrowth), 2) }}% vs Previous Year
                        </small>
                    @endif
                </div>
            </div>

            <!-- Population -->
            <div class="col-md-6 col-lg-4">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Total Population</small>
                    <h3 class="mb-0 text-white">
                        {{ number_format($economic->population) }}
                    </h3>
                    <small class="text-secondary d-block mt-2">World Bank Census</small>
                </div>
            </div>

            <!-- Inflation -->
            <div class="col-md-6 col-lg-4">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Inflation Rate (Consumer Prices)</small>
                    <h3 class="mb-0 {{ $economic->inflation > 10 ? 'text-danger' : ($economic->inflation > 5 ? 'text-warning' : 'text-success') }}">
                        {{ number_format($economic->inflation, 2) }}%
                    </h3>
                    <small class="text-secondary d-block mt-2">Annual percentage change</small>
                </div>
            </div>

            <!-- Merchandise Exports -->
            <div class="col-md-6 col-lg-6">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Merchandise Exports</small>
                    <h3 class="mb-0 text-success">
                        @if($economic->export_value >= 1e12)
                            ${{ number_format($economic->export_value / 1e12, 2) }} Trillion
                        @elseif($economic->export_value >= 1e9)
                            ${{ number_format($economic->export_value / 1e9, 2) }} Billion
                        @elseif($economic->export_value >= 1e6)
                            ${{ number_format($economic->export_value / 1e6, 2) }} Million
                        @else
                            ${{ number_format($economic->export_value, 2) }}
                        @endif
                    </h3>
                    <small class="text-secondary d-block mt-2">Annual trade export value</small>
                </div>
            </div>

            <!-- Merchandise Imports -->
            <div class="col-md-6 col-lg-6">
                <div class="p-3 rounded" style="background: rgba(30, 41, 59, 0.5); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Merchandise Imports</small>
                    <h3 class="mb-0 text-warning">
                        @if($economic->import_value >= 1e12)
                            ${{ number_format($economic->import_value / 1e12, 2) }} Trillion
                        @elseif($economic->import_value >= 1e9)
                            ${{ number_format($economic->import_value / 1e9, 2) }} Billion
                        @elseif($economic->import_value >= 1e6)
                            ${{ number_format($economic->import_value / 1e6, 2) }} Million
                        @else
                            ${{ number_format($economic->import_value, 2) }}
                        @endif
                    </h3>
                    <small class="text-secondary d-block mt-2">Annual trade import value</small>
                </div>
            </div>
        </div>

        <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background: rgba(15, 23, 42, 0.6); border: 1px solid #1e293b;">
            <span style="font-size:12px; color:#64748b;">Data Source: World Bank API</span>
            <span style="font-size:12px; color:#64748b;">Last Updated: {{ $economic->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>
    @else
        <div class="text-center py-5 text-secondary">
            <span style="font-size: 40px;">📭</span>
            <p class="mt-2">No economic data available for this country.</p>
        </div>
    @endif
</div>
