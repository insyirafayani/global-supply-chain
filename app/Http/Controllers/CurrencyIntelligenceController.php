<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyRate;
use App\Models\Country;

class CurrencyIntelligenceController extends Controller
{

    public function index(Request $request)
    {
        // Ensure all 242 countries have a currency_rates record
        $countriesWithoutCurrency = Country::whereDoesntHave('currencyRates')->get();
        foreach ($countriesWithoutCurrency as $country) {
            CurrencyRate::create([
                'country_id'      => $country->id,
                'base_currency'   => 'USD',
                'currency_code'   => $country->currency_code ?? 'USD',
                'currency_name'   => $country->currency ?? 'US Dollar',
                'exchange_rate'   => 1.0,
                'previous_rate'   => 1.0,
                'change_percent'  => 0.0,
                'currency_status' => 'Cost Stable',
                'recorded_at'     => now(),
            ]);
        }

        $query = CurrencyRate::with('country');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('currency_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('country', function ($cq) use ($request) {
                      $cq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('currency_status', $request->status);
        }

        // Filter Region
        if ($request->filled('region')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('region', $request->region);
            });
        }

        $currencies = $query
            ->orderByRaw('CASE WHEN exchange_rate = 1.0 AND change_percent = 0 THEN 1 ELSE 0 END, ABS(change_percent) DESC')
            ->paginate(20)
            ->withQueryString();

        $currencyRates = $currencies;

        // Stats
        $totalTracked   = Country::count();
        $totalCountries = $totalTracked;
        
        $costStable     = CurrencyRate::where('currency_status', 'Cost Stable')->count();
        $costWarning    = CurrencyRate::where('currency_status', 'Cost Warning')->count();
        $costSurge      = CurrencyRate::where('currency_status', 'Cost Surge')->count();
        $tradeCritical  = CurrencyRate::where('currency_status', 'Trade Critical')->count();

        $regions = Country::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $statuses = ['Cost Stable', 'Cost Warning', 'Cost Surge', 'Trade Critical'];

        // Chart & Distribution
        $chartLabels = $statuses;
        $chartValues = [$costStable, $costWarning, $costSurge, $tradeCritical];
        $statusDistribution = [
            'stable' => $costStable,
            'warning' => $costWarning,
            'surge' => $costSurge,
            'critical' => $tradeCritical,
        ];

        return view('currency.index', compact(
            'currencies',
            'currencyRates',
            'regions',
            'statuses',
            'totalTracked',
            'totalCountries',
            'costStable',
            'costWarning',
            'costSurge',
            'tradeCritical',
            'chartLabels',
            'chartValues',
            'statusDistribution'
        ));
    }

}
