@php

$economic = $country->economicData->last();

$weather = $country->weatherData->last();

$currency = $country->currencyRates->last();

$risk = $country->riskScores->last();

$recommendation = $country->recommendations->last();

@endphp

<div class="row">

    <!-- GDP -->

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="card card-dark dashboard-card">

            <div class="card-body text-center">

                <h5>📊 GDP</h5>

                <h3 class="mt-3">

                    @if($economic && $economic->gdp)

                        ${{ number_format($economic->gdp/1000000000000,2) }} T

                    @else

                        -

                    @endif

                </h3>

            </div>

        </div>

    </div>

    <!-- Population -->

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="card card-dark dashboard-card">

            <div class="card-body text-center">

                <h5>👥 Population</h5>

                <h3 class="mt-3">

                    @if($economic)

                        {{ number_format($economic->population) }}

                    @else

                        -

                    @endif

                </h3>

            </div>

        </div>

    </div>

    <!-- Weather -->

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="card card-dark dashboard-card">

            <div class="card-body text-center">

                <h5>☁ Weather</h5>

                <h3 class="mt-3">

                    {{ $weather->temperature ?? '--' }}°C

                </h3>

            </div>

        </div>

    </div>

    <!-- Currency -->

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="card card-dark dashboard-card">

            <div class="card-body text-center">

                <h5>💱 Currency</h5>

                <h3 class="mt-3">

                    {{ $currency->currency_code ?? '-' }}

                </h3>

            </div>

        </div>

    </div>

    <!-- Risk -->

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="card card-dark dashboard-card">

            <div class="card-body text-center">

                <h5>🚨 Risk</h5>

                <h3 class="mt-3">

                    {{ $risk->risk_level ?? 'No Data' }}

                </h3>

            </div>

        </div>

    </div>

    <!-- Recommendation -->

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="card card-dark dashboard-card">

            <div class="card-body text-center">

                <h5>⭐ Recommendation</h5>

                <p class="mt-3">

                    {{ $recommendation->recommendation ?? 'Not Generated' }}

                </p>

            </div>

        </div>

    </div>

    <div class="card card-dark dashboard-card mb-4">

    <div class="card-body">

        <h4>🗺 Country Location</h4>

        <div id="countryMap"
             style="height:450px;border-radius:15px; position:relative;">
        </div>

        @if($country->ports->isEmpty())
            <div class="alert alert-warning mt-3 mb-0 text-center" style="background: rgba(242, 201, 76, 0.1); border: 1px solid rgba(242, 201, 76, 0.3); color: #F2C94C;">
                <i class="fas fa-exclamation-triangle me-2"></i> No registered ports for this country.
            </div>
        @endif

    </div>

</div>

<div class="card card-dark dashboard-card">

<div class="card-body">

<h4>

📰 Latest News

</h4>

@forelse($country->newsCaches->take(5) as $news)

<div class="border-bottom pb-3 mb-3">

<h5>

{{ $news->title }}

</h5>

<p>

{{ Str::limit($news->description,120) }}

</p>

<a href="{{ $news->url }}"
target="_blank"
class="btn btn-outline-info btn-sm">

Read Article

</a>

</div>

@empty

<p>No News</p>

@endforelse

</div>

</div>

</div>