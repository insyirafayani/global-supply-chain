<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskHistory;
use App\Models\EconomicData;
use App\Models\WeatherData;
use App\Models\CurrencyRate;
use App\Models\NewsCache;
use App\Models\TradeRecommendation;

use App\Services\WeatherService;
use App\Services\EconomicService;
use App\Services\CurrencyService;
use App\Services\NewsService;
use App\Services\SentimentService;
use App\Services\RiskService;
use App\Services\RecommendationService;

class RiskAnalyticsController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | INDEX — Initial Page Load
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // Eager load all relations in ONE query to avoid N+1
        $riskScores = RiskScore::with([
            'country' => function ($q) {
                $q->select(['id', 'name', 'iso2', 'flag', 'region', 'subregion', 'latitude', 'longitude', 'currency_code'])
                  ->with([
                      'economicData'    => fn($q) => $q->select(['id', 'country_id', 'gdp', 'inflation', 'year'])->latest('year')->limit(1),
                      'weatherData'     => fn($q) => $q->select(['id', 'country_id', 'temperature', 'rainfall', 'weather_status'])->latest()->limit(1),
                      'currencyRates'   => fn($q) => $q->select(['id', 'country_id', 'currency_code', 'exchange_rate', 'change_percent', 'currency_status'])->latest()->limit(1),
                      'recommendations' => fn($q) => $q->select(['id', 'country_id', 'recommendation', 'priority', 'confidence'])->latest()->limit(1),
                  ]);
            }
        ])
        ->select(['id', 'country_id', 'weather_risk', 'inflation_risk', 'news_risk', 'currency_risk', 'total_score', 'risk_level', 'updated_at'])
        ->orderByDesc('total_score')
        ->get();

        // --- Summary KPIs ---
        $highRisk    = $riskScores->where('risk_level', 'High Risk')->count();
        $mediumRisk  = $riskScores->where('risk_level', 'Medium Risk')->count();
        $lowRisk     = $riskScores->where('risk_level', 'Low Risk')->count();
        $totalMonitored = $riskScores->count();

        // --- Top 10 with trend ---
        $topCountries = $riskScores->take(10)->map(function ($rs) {
            $prevHistory = RiskHistory::where('country_id', $rs->country_id)
                ->orderByDesc('date')
                ->skip(1)->first();

            $trend = '→';
            if ($prevHistory) {
                if ($rs->total_score > $prevHistory->risk_score) $trend = '↑';
                elseif ($rs->total_score < $prevHistory->risk_score) $trend = '↓';
            }

            return [
                'id'         => $rs->country_id,
                'name'       => $rs->country?->name,
                'flag'       => $rs->country?->flag,
                'region'     => $rs->country?->region,
                'total_score'=> $rs->total_score,
                'risk_level' => $rs->risk_level,
                'trend'      => $trend,
            ];
        });

        // --- Regions ---
        $regions = Country::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        // --- Chart labels/values for initial render ---
        $top20 = $riskScores->take(20);
        $chartLabels = $top20->map(fn($r) => $r->country?->name ?? 'Unknown');
        $chartValues = $top20->map(fn($r) => $r->total_score);
        $chartColors = $top20->map(function ($r) {
            return match ($r->risk_level) {
                'High Risk'   => '#ef4444',
                'Medium Risk' => '#f59e0b',
                default       => '#22c55e',
            };
        });

        // --- Map Data (pre-computed to avoid @json(function(){}) in Blade) ---
        $riskMapData = $riskScores->map(function ($rs) {
            $eco     = $rs->country?->economicData->first();
            $curr    = $rs->country?->currencyRates->first();
            $rec     = $rs->country?->recommendations->first();
            $weather = $rs->country?->weatherData->first();

            return [
                'country_id'     => $rs->country_id,
                'name'           => $rs->country?->name,
                'flag'           => $rs->country?->flag,
                'lat'            => $rs->country?->latitude,
                'lng'            => $rs->country?->longitude,
                'total_score'    => $rs->total_score,
                'risk_level'     => $rs->risk_level,
                'temperature'    => $weather?->temperature,
                'currency'       => $curr?->currency_code ?? $rs->country?->currency_code,
                'exchange_rate'  => $curr?->exchange_rate,
                'gdp'            => $eco?->gdp,
                'recommendation' => $rec?->recommendation,
            ];
        })->values()->toArray();

        return view('risk.index', compact(
            'riskScores',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'totalMonitored',
            'chartLabels',
            'chartValues',
            'chartColors',
            'topCountries',
            'regions',
            'riskMapData'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | API DATA — AJAX JSON endpoint for all widgets
    |--------------------------------------------------------------------------
    */

    public function apiData(Request $request)
    {
        $period = (int) $request->get('period', 30); // days

        // Single query — all relations
        $riskScores = RiskScore::with([
            'country' => function ($q) {
                $q->select(['id', 'name', 'iso2', 'flag', 'region', 'subregion', 'latitude', 'longitude', 'currency_code'])
                  ->with([
                      'economicData'    => fn($q) => $q->select(['id', 'country_id', 'gdp', 'inflation', 'year'])->latest('year')->limit(1),
                      'weatherData'     => fn($q) => $q->select(['id', 'country_id', 'temperature', 'rainfall', 'weather_status'])->latest()->limit(1),
                      'currencyRates'   => fn($q) => $q->select(['id', 'country_id', 'currency_code', 'exchange_rate', 'change_percent', 'currency_status'])->latest()->limit(1),
                      'recommendations' => fn($q) => $q->select(['id', 'country_id', 'recommendation', 'priority', 'confidence'])->latest()->limit(1),
                  ]);
            }
        ])
        ->select(['id', 'country_id', 'weather_risk', 'inflation_risk', 'news_risk', 'currency_risk', 'total_score', 'risk_level', 'updated_at'])
        ->orderByDesc('total_score')
        ->get();

        $totalMonitored = $riskScores->count();

        // --- Summary ---
        $highRisk   = $riskScores->where('risk_level', 'High Risk')->count();
        $mediumRisk = $riskScores->where('risk_level', 'Medium Risk')->count();
        $lowRisk    = $riskScores->where('risk_level', 'Low Risk')->count();

        $avgRisk    = $totalMonitored ? round($riskScores->avg('total_score'), 1) : 0;
        $highest    = $riskScores->max('total_score') ?? 0;
        $lowest     = $riskScores->min('total_score') ?? 0;

        $avgInflation = $riskScores->map(fn($r) => $r->country?->economicData->first()?->inflation ?? null)
            ->filter()->avg() ?? 0;
        $avgTemp = $riskScores->map(fn($r) => $r->country?->weatherData->first()?->temperature ?? null)
            ->filter()->avg() ?? 0;
        $avgRate = $riskScores->map(fn($r) => $r->country?->currencyRates->first()?->exchange_rate ?? null)
            ->filter()->avg() ?? 0;

        // --- Trend Chart (Top 20) ---
        $top20 = $riskScores->take(20);
        $trendDetails = $top20->map(function ($r) {
            $eco     = $r->country?->economicData->first();
            $weather = $r->country?->weatherData->first();
            $curr    = $r->country?->currencyRates->first();
            return [
                'country'     => $r->country?->name ?? 'N/A',
                'score'       => $r->total_score,
                'level'       => $r->risk_level,
                'temperature' => $weather?->temperature,
                'currency'    => $curr?->currency_code ?? $r->country?->currency_code,
                'gdp'         => $eco?->gdp,
                'updated_at'  => $r->updated_at?->diffForHumans(),
                'country_id'  => $r->country_id,
            ];
        });

        $trendChart = [
            'labels'  => $top20->map(fn($r) => $r->country?->name ?? 'Unknown')->values(),
            'data'    => $top20->map(fn($r) => $r->total_score)->values(),
            'colors'  => $top20->map(function ($r) {
                return match ($r->risk_level) {
                    'High Risk'   => '#ef4444',
                    'Medium Risk' => '#f59e0b',
                    default       => '#22c55e',
                };
            })->values(),
            'details' => $trendDetails->values(),
        ];

        // --- Distribution Chart ---
        $distributionChart = [
            'high'   => $highRisk,
            'medium' => $mediumRisk,
            'low'    => $lowRisk,
        ];

        // --- Regional Chart ---
        $regionalData = $riskScores->groupBy(fn($r) => $r->country?->region ?? 'Unknown')
            ->map(fn($group, $region) => [
                'region' => $region,
                'avg'    => round($group->avg('total_score'), 1),
                'count'  => $group->count(),
            ])
            ->filter(fn($v) => $v['region'] !== 'Unknown')
            ->sortByDesc('avg')
            ->values();

        $regionalChart = [
            'labels' => $regionalData->pluck('region')->values(),
            'data'   => $regionalData->pluck('avg')->values(),
            'counts' => $regionalData->pluck('count')->values(),
        ];

        // --- Radar Chart (Risk Factor Averages) ---
        $radarChart = [
            'weather'  => $totalMonitored ? round($riskScores->avg('weather_risk'), 1) : 0,
            'economy'  => $totalMonitored ? round($riskScores->avg('inflation_risk'), 1) : 0,
            'currency' => $totalMonitored ? round($riskScores->avg('currency_risk'), 1) : 0,
            'news'     => $totalMonitored ? round($riskScores->avg('news_risk'), 1) : 0,
        ];

        // --- Top 10 Countries with Trend ---
        $topCountries = $riskScores->take(10)->map(function ($rs) {
            $prevHistory = RiskHistory::where('country_id', $rs->country_id)
                ->orderByDesc('date')
                ->skip(1)->first();

            $trend = '→';
            if ($prevHistory) {
                if ($rs->total_score > $prevHistory->risk_score)  $trend = '↑';
                elseif ($rs->total_score < $prevHistory->risk_score) $trend = '↓';
            }
            $eco  = $rs->country?->economicData->first();
            $curr = $rs->country?->currencyRates->first();
            $rec  = $rs->country?->recommendations->first();

            return [
                'id'             => $rs->country_id,
                'name'           => $rs->country?->name,
                'flag'           => $rs->country?->flag,
                'region'         => $rs->country?->region,
                'total_score'    => $rs->total_score,
                'risk_level'     => $rs->risk_level,
                'trend'          => $trend,
                'gdp'            => $eco?->gdp,
                'currency'       => $curr?->currency_code ?? $rs->country?->currency_code,
                'recommendation' => $rec?->recommendation,
            ];
        })->values();

        // --- Leaderboard (all countries) ---
        $leaderboard = $riskScores->map(function ($rs, $idx) {
            $eco     = $rs->country?->economicData->first();
            $weather = $rs->country?->weatherData->first();
            $curr    = $rs->country?->currencyRates->first();
            $rec     = $rs->country?->recommendations->first();

            return [
                'rank'           => $idx + 1,
                'id'             => $rs->country_id,
                'name'           => $rs->country?->name ?? 'Unknown',
                'flag'           => $rs->country?->flag,
                'region'         => $rs->country?->region,
                'total_score'    => $rs->total_score,
                'risk_level'     => $rs->risk_level,
                'temperature'    => $weather?->temperature,
                'currency'       => $curr?->currency_code ?? $rs->country?->currency_code,
                'exchange_rate'  => $curr?->exchange_rate,
                'gdp'            => $eco?->gdp,
                'recommendation' => $rec?->recommendation ?? 'N/A',
            ];
        })->values();

        // --- Live Alerts (10 most recent negative news) ---
        $alerts = NewsCache::with(['country:id,name,flag'])
            ->where('sentiment', 'Negative')
            ->latest('published_at')
            ->limit(10)
            ->get(['id', 'country_id', 'title', 'description', 'sentiment', 'published_at'])
            ->map(fn($n) => [
                'country_id'   => $n->country_id,
                'country_name' => $n->country?->name ?? 'Global',
                'country_flag' => $n->country?->flag,
                'title'        => $n->title,
                'description'  => \Str::limit($n->description ?? $n->title, 80),
                'time_ago'     => $n->published_at?->diffForHumans() ?? 'Recently',
            ]);

        // --- AI Recommendation Summary ---
        $recs        = TradeRecommendation::select(['id', 'country_id', 'recommendation', 'priority', 'confidence', 'updated_at'])->get();
        $recCount    = $recs->count();
        $recAvoidCount    = $recs->filter(fn($r) => str_contains(strtolower($r->recommendation), 'avoid'))->count();
        $recMonitorCount  = $recs->filter(fn($r) => str_contains(strtolower($r->priority), 'medium'))->count();
        $recSuitableCount = $recs->filter(fn($r) => str_contains(strtolower($r->recommendation), 'suitable') || str_contains(strtolower($r->recommendation), 'expand'))->count();

        $aiRecommendation = [
            'total'          => $recCount,
            'recommended'    => $recSuitableCount,
            'monitoring'     => $recMonitorCount,
            'avoid'          => $recAvoidCount,
            'avg_confidence' => $recCount ? round($recs->avg('confidence'), 1) : 0,
            'generated_at'   => $recs->max('updated_at') ? \Carbon\Carbon::parse($recs->max('updated_at'))->diffForHumans() : 'N/A',
        ];

        // --- API Status ---
        $apiStatus = [
            'weather'  => WeatherData::count() > 0,
            'economic' => EconomicData::count() > 0,
            'currency' => CurrencyRate::count() > 0,
            'news'     => NewsCache::count() > 0,
        ];

        return response()->json([
            'summary' => [
                'total'        => $totalMonitored,
                'high'         => $highRisk,
                'medium'       => $mediumRisk,
                'low'          => $lowRisk,
                'avg'          => $avgRisk,
                'highest'      => $highest,
                'lowest'       => $lowest,
                'avgInflation' => round($avgInflation, 2),
                'avgTemp'      => round($avgTemp, 1),
                'avgRate'      => round($avgRate, 2),
            ],
            'trendChart'        => $trendChart,
            'distributionChart' => $distributionChart,
            'regionalChart'     => $regionalChart,
            'radarChart'        => $radarChart,
            'topCountries'      => $topCountries,
            'leaderboard'       => $leaderboard,
            'alerts'            => $alerts,
            'aiRecommendation'  => $aiRecommendation,
            'apiStatus'         => $apiStatus,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REFRESH ALL — Trigger all services for all countries
    |--------------------------------------------------------------------------
    */

    public function refreshAll(
        Request             $request,
        WeatherService      $weatherService,
        EconomicService     $economicService,
        CurrencyService     $currencyService,
        NewsService         $newsService,
        SentimentService    $sentimentService,
        RiskService         $riskService,
        RecommendationService $recommendationService
    ) {
        // Only refresh countries that already have some data (not brand new)
        $countries = Country::whereHas('riskScores')
            ->orWhereHas('economicData')
            ->orWhereHas('weatherData')
            ->orWhereHas('currencyRates')
            ->get();

        if ($countries->isEmpty()) {
            // Fallback: use all countries
            $countries = Country::all();
        }

        $results = [
            'weather'        => 0,
            'economic'       => 0,
            'currency'       => 0,
            'news'           => 0,
            'risk'           => 0,
            'recommendation' => 0,
            'errors'         => [],
        ];

        foreach ($countries as $country) {

            try {
                $weatherService->sync($country);
                $results['weather']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Weather({$country->name}): " . $e->getMessage();
            }

            try {
                $economicService->sync($country);
                $results['economic']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Economic({$country->name}): " . $e->getMessage();
            }

            try {
                $currencyService->sync($country);
                $results['currency']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Currency({$country->name}): " . $e->getMessage();
            }

            try {
                $newsService->sync($country, $sentimentService);
                $results['news']++;
            } catch (\Exception $e) {
                $results['errors'][] = "News({$country->name}): " . $e->getMessage();
            }

            try {
                $country->load(['weatherData', 'economicData', 'news']);
                $riskScore = $riskService->calculate($country);

                // Save to risk_histories for trend tracking
                RiskHistory::updateOrCreate(
                    ['country_id' => $country->id, 'date' => now()->toDateString()],
                    ['risk_score' => $riskScore->total_score]
                );
                $results['risk']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Risk({$country->name}): " . $e->getMessage();
            }

            try {
                $country->load(['riskScores', 'currencyRates', 'economicData', 'weatherData']);
                $recommendationService->generate($country);
                $results['recommendation']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Rec({$country->name}): " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Refreshed {$countries->count()} countries",
            'results' => $results,
        ]);
    }

}