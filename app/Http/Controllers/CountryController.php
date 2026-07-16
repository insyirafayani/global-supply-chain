<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

use App\Services\EconomicService;
use App\Services\WeatherService;
use App\Services\CurrencyService;
use App\Services\NewsService;
use App\Services\SentimentService;
use App\Services\RiskService;
use App\Services\RecommendationService;
use App\Services\CountryIntelligenceService;

class CountryController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | COUNTRY MONITOR
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Country::query();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('iso2', 'like', "%{$search}%")
                    ->orWhere('currency_code', 'like', "%{$search}%");
            });
        }

        // FILTER REGION
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // LOAD RELATION PAGINATED
        $countries = $query
            ->with([
                'riskScores',
                'economicData',
                'weatherData',
                'currencyRates',
                'newsCaches',
                'recommendations'
            ])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Auto-sync missing indicators in real-time for current page view
        $intelService = app(CountryIntelligenceService::class);
        $needsReload = false;
        foreach ($countries as $c) {
            if ($c->economicData->isEmpty() || $c->weatherData->isEmpty() || $c->currencyRates->isEmpty() || $c->riskScores->isEmpty()) {
                try {
                    $intelService->sync($c);
                    $needsReload = true;
                } catch (\Exception $e) {
                    \Log::error("Failed auto-sync on index for {$c->name}: " . $e->getMessage());
                }
            }
        }

        if ($needsReload) {
            // Reload collection relations
            $countries->load([
                'riskScores',
                'economicData',
                'weatherData',
                'currencyRates',
                'newsCaches',
                'recommendations'
            ]);
        }

        $regions = Country::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $totalCountries = Country::count();

        $lowRisk = Country::whereHas('riskScores', function ($q) {
            $q->where('risk_level', 'Low Risk');
        })->count();

        $mediumRisk = Country::whereHas('riskScores', function ($q) {
            $q->where('risk_level', 'Medium Risk');
        })->count();

        $highRisk = Country::whereHas('riskScores', function ($q) {
            $q->where('risk_level', 'High Risk');
        })->count();

        $totalNews = \App\Models\NewsCache::count();
        $totalWeather = \App\Models\WeatherData::count();
        $totalCurrency = \App\Models\CurrencyRate::count();
        $totalEconomic = \App\Models\EconomicData::count();

        return view('countries.index', compact(
            'countries',
            'regions',
            'totalCountries',
            'lowRisk',
            'mediumRisk',
            'highRisk',
            'totalNews',
            'totalWeather',
            'totalCurrency',
            'totalEconomic'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | COUNTRY DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $country = Country::findOrFail($id);

        // Load dynamic real-time country intelligence with 24h caching
        CountryIntelligenceService::load($country);

        $country->load([
            'economicData',
            'weatherData',
            'currencyRates',
            'news',
            'riskScores',
            'recommendations',
            'ports'
        ]);

        return view('countries.show', compact('country'));
    }

    /*
    |--------------------------------------------------------------------------
    | WEATHER
    |--------------------------------------------------------------------------
    */

    public function weather($id)
    {

        $country = Country::with('weatherData')
            ->findOrFail($id);

        return view(
            'countries.weather',
            compact('country')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | CURRENCY
    |--------------------------------------------------------------------------
    */

    public function currency($id)
    {

        $country = Country::with('currencyRates')
            ->findOrFail($id);

        return view(
            'countries.currency',
            compact('country')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | NEWS
    |--------------------------------------------------------------------------
    */

    public function news($id)
    {

        $country = Country::with('newsCaches')
            ->findOrFail($id);

        return view(
            'countries.news',
            compact('country')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | RECOMMENDATION
    |--------------------------------------------------------------------------
    */

    public function recommendation($id)
    {

        $country = Country::with('recommendations')
            ->findOrFail($id);

        return view(
            'countries.recommendation',
            compact('country')
        );

    }

}