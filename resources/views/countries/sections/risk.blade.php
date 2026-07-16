@php
$risk = $country->riskScores->last();
@endphp

<div class="row mb-4">

    {{-- TOTAL SCORE --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card card-dark dashboard-card h-100">
            <div class="card-body text-center p-4">
                <div style="font-size:48px; margin-bottom:10px;">🚨</div>
                <h6 class="text-secondary mb-2">Total Risk Score</h6>
                <h1 class="fw-bold mb-0"
                    style="font-size:56px; color:
                    @if($risk && $risk->risk_level=='High Risk') #ef4444
                    @elseif($risk && $risk->risk_level=='Medium Risk') #f59e0b
                    @else #22c55e @endif">
                    {{ $risk ? round($risk->total_score) : 'N/A' }}
                </h1>
                <p class="text-secondary small mt-1">out of 100</p>
            </div>
        </div>
    </div>

    {{-- RISK LEVEL --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card card-dark dashboard-card h-100">
            <div class="card-body text-center p-4">
                <div style="font-size:48px; margin-bottom:10px;">
                    @if($risk && $risk->risk_level=='High Risk') 🔴
                    @elseif($risk && $risk->risk_level=='Medium Risk') 🟡
                    @else 🟢 @endif
                </div>
                <h6 class="text-secondary mb-2">Risk Level</h6>
                <h3 class="fw-bold"
                    style="color:
                    @if($risk && $risk->risk_level=='High Risk') #ef4444
                    @elseif($risk && $risk->risk_level=='Medium Risk') #f59e0b
                    @else #22c55e @endif">
                    {{ $risk ? $risk->risk_level : 'No Data' }}
                </h3>
                <small class="text-secondary">Based on Risk Engine v1.0</small>
            </div>
        </div>
    </div>

    {{-- LAST UPDATED --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card card-dark dashboard-card h-100">
            <div class="card-body text-center p-4">
                <div style="font-size:48px; margin-bottom:10px;">🕐</div>
                <h6 class="text-secondary mb-2">Last Updated</h6>
                <h5 class="fw-bold text-info">
                    {{ $risk ? $risk->updated_at->format('d M Y') : 'Never' }}
                </h5>
                <small class="text-secondary">
                    {{ $risk ? $risk->updated_at->format('H:i') . ' UTC' : 'No calculation yet' }}
                </small>
            </div>
        </div>
    </div>

</div>

{{-- RISK BREAKDOWN --}}
@if($risk)
<div class="card card-dark dashboard-card mb-4">
    <div class="card-body p-4">

        <h4 class="mb-4">📊 Risk Score Breakdown</h4>

        <div class="row">

            {{-- WEATHER RISK 30% --}}
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>☁ Weather Risk <span class="text-secondary small">(30%)</span></span>
                    <strong style="color:#38bdf8">{{ round($risk->weather_risk) }}</strong>
                </div>
                <div class="progress" style="height:10px; border-radius:8px; background:#1e293b">
                    <div class="progress-bar"
                         style="width:{{ $risk->weather_risk }}%;
                                background:linear-gradient(90deg,#2563eb,#38bdf8);
                                border-radius:8px;">
                    </div>
                </div>
                <small class="text-secondary mt-1 d-block">
                    @if($risk->weather_risk >= 66) ⚠ High weather hazard
                    @elseif($risk->weather_risk >= 36) ⚡ Moderate weather conditions
                    @else ✅ Favorable weather conditions
                    @endif
                </small>
            </div>

            {{-- INFLATION RISK 20% --}}
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>📈 Economic Risk <span class="text-secondary small">(20%)</span></span>
                    <strong style="color:#f59e0b">{{ round($risk->inflation_risk) }}</strong>
                </div>
                <div class="progress" style="height:10px; border-radius:8px; background:#1e293b">
                    <div class="progress-bar"
                         style="width:{{ $risk->inflation_risk }}%;
                                background:linear-gradient(90deg,#d97706,#f59e0b);
                                border-radius:8px;">
                    </div>
                </div>
                <small class="text-secondary mt-1 d-block">
                    @if($risk->inflation_risk >= 66) ⚠ High inflation risk
                    @elseif($risk->inflation_risk >= 36) ⚡ Moderate economic risk
                    @else ✅ Stable economic indicators
                    @endif
                </small>
            </div>

            {{-- CURRENCY RISK 10% --}}
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>💱 Currency Risk <span class="text-secondary small">(10%)</span></span>
                    <strong style="color:#a855f7">{{ round($risk->currency_risk) }}</strong>
                </div>
                <div class="progress" style="height:10px; border-radius:8px; background:#1e293b">
                    <div class="progress-bar"
                         style="width:{{ $risk->currency_risk }}%;
                                background:linear-gradient(90deg,#7c3aed,#a855f7);
                                border-radius:8px;">
                    </div>
                </div>
                <small class="text-secondary mt-1 d-block">
                    @if($risk->currency_risk >= 66) ⚠ High currency volatility
                    @elseif($risk->currency_risk >= 36) ⚡ Moderate currency risk
                    @else ✅ Currency relatively stable
                    @endif
                </small>
            </div>

            {{-- NEWS RISK 40% --}}
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>📰 News Sentiment Risk <span class="text-secondary small">(40%)</span></span>
                    <strong style="color:#ef4444">{{ round($risk->news_risk) }}</strong>
                </div>
                <div class="progress" style="height:10px; border-radius:8px; background:#1e293b">
                    <div class="progress-bar"
                         style="width:{{ $risk->news_risk }}%;
                                background:linear-gradient(90deg,#b91c1c,#ef4444);
                                border-radius:8px;">
                    </div>
                </div>
                <small class="text-secondary mt-1 d-block">
                    @if($risk->news_risk >= 66) ⚠ Predominantly negative news
                    @elseif($risk->news_risk >= 36) ⚡ Mixed news sentiment
                    @else ✅ Positive news sentiment
                    @endif
                </small>
            </div>

        </div>

        {{-- FORMULA --}}
        <div class="mt-2 p-3" style="background:#0f172a; border-radius:12px; border:1px solid #1e293b">
            <small class="text-secondary">
                ⚙ Formula: Total Score =
                Weather({{ round($risk->weather_risk) }}×30%) +
                Economic({{ round($risk->inflation_risk) }}×20%) +
                Currency({{ round($risk->currency_risk) }}×10%) +
                News({{ round($risk->news_risk) }}×40%) =
                <strong style="color:#38bdf8">{{ round($risk->total_score) }}</strong>
            </small>
        </div>

    </div>
</div>

{{-- RISK THRESHOLD GUIDE --}}
<div class="card card-dark dashboard-card">
    <div class="card-body p-4">
        <h4 class="mb-4">📋 Risk Level Guide</h4>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3" style="background:#052e16; border-radius:12px; border:1px solid #166534">
                    <div style="font-size:32px">🟢</div>
                    <h5 class="text-success mt-2">Low Risk</h5>
                    <p class="text-secondary small mb-0">Score 0 – 35</p>
                    <p class="text-secondary small">Safe for trade expansion</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3" style="background:#431407; border-radius:12px; border:1px solid #92400e">
                    <div style="font-size:32px">🟡</div>
                    <h5 class="text-warning mt-2">Medium Risk</h5>
                    <p class="text-secondary small mb-0">Score 36 – 65</p>
                    <p class="text-secondary small">Proceed with caution</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3" style="background:#450a0a; border-radius:12px; border:1px solid #7f1d1d">
                    <div style="font-size:32px">🔴</div>
                    <h5 class="text-danger mt-2">High Risk</h5>
                    <p class="text-secondary small mb-0">Score 66 – 100</p>
                    <p class="text-secondary small">Delay trade decisions</p>
                </div>
            </div>
        </div>
    </div>
</div>

@else

{{-- EMPTY STATE --}}
<div class="card card-dark dashboard-card">
    <div class="card-body text-center p-5">
        <div style="font-size:64px; margin-bottom:20px; opacity:0.4">🚨</div>
        <h4 class="text-secondary">Risk Score Not Available</h4>
        <p class="text-secondary">Risk intelligence data is being processed. Please refresh the page.</p>
    </div>
</div>

@endif