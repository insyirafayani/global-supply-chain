<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\NewsCache;
use App\Models\WeatherData;
use App\Models\RiskScore;
use App\Models\EconomicData;
use App\Models\CurrencyRate;
use App\Models\TradeRecommendation;
use App\Models\Port;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCountries = Country::count();
        $totalNews = NewsCache::count();
        $weatherCount = WeatherData::count();
        $riskCount = RiskScore::count();

        // Eager load ALL relations to avoid N+1 queries when building map markers
        $countries = Country::with([
            'riskScores' => fn($q) => $q->latest()->limit(1),
            'weatherData' => fn($q) => $q->latest()->limit(1),
            'currencyRates' => fn($q) => $q->latest()->limit(1),
            'economicData' => fn($q) => $q->latest()->limit(1),
            'recommendations' => fn($q) => $q->latest()->limit(1),
            'ports'
        ])->get();

        $mapCountries = $countries->map(function ($c) {
            $risk = $c->riskScores->first();
            $weather = $c->weatherData->first();
            $eco = $c->economicData->first();
            $rec = $c->recommendations->first();

            return [
                'id' => $c->id,
                'name' => $c->name,
                'capital' => $c->capital,
                'region' => $c->region,
                'lat' => $c->latitude,
                'lng' => $c->longitude,
                'flag' => $c->flag,
                'gdp' => $eco ? $eco->gdp : null,
                'population' => $eco ? $eco->population : null,
                'inflation' => $eco ? $eco->inflation : null,
                'currency' => $c->currency_code,
                'temperature' => $weather ? $weather->temperature : null,
                'weather_status' => $weather ? $weather->weather_status : 'Normal',
                'risk_score' => $risk ? $risk->total_score : null,
                'risk_level' => $risk ? $risk->risk_level : 'Unknown',
                'recommendation' => $rec ? $rec->recommendation : 'Monitor economic and logistics indicators',
                'ports' => $c->ports->map(fn($p) => [
                    'name' => $p->port_name,
                    'code' => $p->port_code,
                    'lat' => $p->latitude,
                    'lng' => $p->longitude,
                    'location' => $p->location,
                    'status' => $p->status ?? 'Open',
                    'capacity' => $p->capacity ? number_format($p->capacity) . ' TEU' : '—',
                    'congestion' => $p->congestion ?? 'Low',
                    'port_type' => $p->port_type ?? 'Container',
                    'risk' => $p->risk ?? 'Low Risk'
                ])
            ];
        })->values()->toArray();

        // Stats for Dashboard Cards
        $newsCount = NewsCache::count();
        $positiveNews = NewsCache::where('sentiment', 'Positive')->count();
        $neutralNews = NewsCache::where('sentiment', 'Neutral')->count();
        $negativeNews = NewsCache::where('sentiment', 'Negative')->count();
        $latestNews = NewsCache::latest()->take(5)->get();

        $latestRecommendation = TradeRecommendation::with('country')
            ->latest()
            ->first();

        // Additional stats
        $highRiskCount = RiskScore::where('risk_level', 'High Risk')->count();
        $mediumRiskCount = RiskScore::where('risk_level', 'Medium Risk')->count();
        $lowRiskCount = RiskScore::where('risk_level', 'Low Risk')->count();

        return view('dashboard.index', compact(
            'totalCountries',
            'totalNews',
            'weatherCount',
            'riskCount',
            'countries',
            'newsCount',
            'positiveNews',
            'neutralNews',
            'negativeNews',
            'latestNews',
            'latestRecommendation',
            'highRiskCount',
            'mediumRiskCount',
            'lowRiskCount',
            'mapCountries'
        ));
    }

    public function countryData($id)
    {
        $c = Country::with([
            'riskScores' => fn($q) => $q->latest(),
            'weatherData' => fn($q) => $q->latest(),
            'currencyRates' => fn($q) => $q->latest(),
            'economicData' => fn($q) => $q->latest(),
            'recommendations' => fn($q) => $q->latest(),
            'ports'
        ])->findOrFail($id);

        // We could also call CountryIntelligenceService::load($c) here if we want to force API sync, 
        // but fetching latest DB records is the main requirement for realtime without page reload.

        $risk = $c->riskScores->first();
        $weather = $c->weatherData->first();
        $eco = $c->economicData->first();
        $rec = $c->recommendations->first();

        $lastUpdated = collect([
            $c->updated_at,
            $risk ? $risk->updated_at : null,
            $weather ? $weather->updated_at : null,
            $eco ? $eco->updated_at : null,
            $rec ? $rec->updated_at : null,
        ])->filter()->max();

        return response()->json([
            'id' => $c->id,
            'name' => $c->name,
            'capital' => $c->capital,
            'region' => $c->region,
            'lat' => $c->latitude,
            'lng' => $c->longitude,
            'flag' => $c->flag,
            'gdp' => $eco ? $eco->gdp : null,
            'population' => $eco ? $eco->population : null,
            'inflation' => $eco ? $eco->inflation : null,
            'currency' => $c->currency_code,
            'temperature' => $weather ? $weather->temperature : null,
            'weather_status' => $weather ? $weather->weather_status : null,
            'risk_score' => $risk ? $risk->total_score : null,
            'risk_level' => $risk ? $risk->risk_level : null,
            'recommendation' => $rec ? $rec->recommendation : null,
            'last_updated' => $lastUpdated ? $lastUpdated->diffForHumans() : 'Unknown',
        ]);
    }
}