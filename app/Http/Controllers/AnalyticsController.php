<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\EconomicData;
use App\Models\CurrencyRate;
use App\Models\WeatherData;
use App\Models\RiskScore;
use App\Models\RiskHistory;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // 1. GDP & Inflation Trend (Economic Data)
        $economicStats = EconomicData::select('year', 
            DB::raw('AVG(gdp) as avg_gdp'), 
            DB::raw('AVG(inflation) as avg_inflation'),
            DB::raw('AVG(population) as avg_population'),
            DB::raw('AVG(export_value) as avg_export'),
            DB::raw('AVG(import_value) as avg_import')
        )
        ->groupBy('year')
        ->orderBy('year', 'asc')
        ->get();

        $gdpYears = $economicStats->pluck('year')->toArray();
        $avgGdp = $economicStats->pluck('avg_gdp')->toArray();
        $avgInflation = $economicStats->pluck('avg_inflation')->toArray();
        $avgPopulation = $economicStats->pluck('avg_population')->toArray();
        $avgExport = $economicStats->pluck('avg_export')->toArray();
        $avgImport = $economicStats->pluck('avg_import')->toArray();

        // 2. Currency Rates Trend
        $currencyRates = CurrencyRate::select('currency_code', DB::raw('AVG(exchange_rate) as avg_rate'))
            ->groupBy('currency_code')
            ->orderBy('avg_rate', 'desc')
            ->limit(10)
            ->get();

        $currencyLabels = $currencyRates->pluck('currency_code')->toArray();
        $currencyValues = $currencyRates->pluck('avg_rate')->toArray();

        // 3. Weather Trend (Temp & Rainfall by region/average)
        $weatherData = WeatherData::with('country')
            ->select('country_id', 'temperature', 'rainfall')
            ->whereNotNull('temperature')
            ->limit(15)
            ->get();

        $weatherLabels = $weatherData->map(fn($w) => $w->country?->name ?? 'Unknown')->toArray();
        $weatherTemp = $weatherData->pluck('temperature')->toArray();
        $weatherRain = $weatherData->pluck('rainfall')->toArray();

        // 4. Risk Trend
        $riskHistories = RiskHistory::select(
                DB::raw("DATE_FORMAT(date, '%m-%d') as date_formatted"), 
                DB::raw('AVG(risk_score) as avg_score')
            )
            ->groupBy(DB::raw("DATE_FORMAT(date, '%m-%d')"))
            ->orderBy('date_formatted', 'asc')
            ->limit(10)
            ->get();

        $riskLabels = $riskHistories->pluck('date_formatted')->toArray();
        $riskValues = $riskHistories->pluck('avg_score')->toArray();

        // Fallbacks if empty trends
        if (empty($gdpYears)) {
            $gdpYears = [2022, 2023, 2024, 2025];
            $avgGdp = [1.2e12, 1.3e12, 1.4e12, 1.5e12];
            $avgInflation = [4.2, 5.1, 3.8, 2.5];
            $avgPopulation = [80000000, 81000000, 82000000, 83000000];
            $avgExport = [2.5e11, 2.7e11, 2.9e11, 3.1e11];
            $avgImport = [2.3e11, 2.5e11, 2.6e11, 2.8e11];
        }

        if (empty($riskLabels)) {
            $riskLabels = ['01 Jul', '05 Jul', '10 Jul', '15 Jul'];
            $riskValues = [45, 48, 42, 40];
        }

        return view('analytics.index', compact(
            'gdpYears',
            'avgGdp',
            'avgInflation',
            'avgPopulation',
            'avgExport',
            'avgImport',
            'currencyLabels',
            'currencyValues',
            'weatherLabels',
            'weatherTemp',
            'weatherRain',
            'riskLabels',
            'riskValues'
        ));
    }
}
