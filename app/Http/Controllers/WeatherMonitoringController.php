<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;
use App\Models\Country;

class WeatherMonitoringController extends Controller
{

    public function index(Request $request)
    {
        // Ensure all 242 countries have a weather_data record
        $countriesWithoutWeather = Country::whereDoesntHave('weatherData')->get();
        foreach ($countriesWithoutWeather as $country) {
            WeatherData::create([
                'country_id'     => $country->id,
                'temperature'    => null,
                'rainfall'       => null,
                'wind_speed'     => null,
                'humidity'       => null,
                'pressure'       => null,
                'weather_status' => 'Normal',
                'recorded_at'    => now(),
            ]);
        }

        $query = WeatherData::with('country');

        // Filter search country name
        if ($request->filled('search')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Region
        if ($request->filled('region')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('region', $request->region);
            });
        }

        // Filter Temperature
        if ($request->filled('temp_filter')) {
            if ($request->temp_filter === 'hot') {
                $query->where('temperature', '>', 30);
            } elseif ($request->temp_filter === 'cold') {
                $query->where('temperature', '<', 10);
            } elseif ($request->temp_filter === 'extreme') {
                $query->where(function ($q) {
                    $q->where('temperature', '>', 35)
                      ->orWhere('temperature', '<', 0);
                });
            }
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('weather_status', $request->status);
        }

        $weatherData = $query
            ->orderByRaw('CASE WHEN temperature IS NULL THEN 1 ELSE 0 END, temperature DESC')
            ->paginate(20)
            ->withQueryString();

        // Perform dynamic real-time batch weather sync for countries on the current page that lack data or have stale data (>24h)
        $syncCountries = [];
        foreach ($weatherData as $wd) {
            if ($wd->temperature === null || $wd->updated_at->lt(now()->subHours(24))) {
                if ($wd->country) {
                    $syncCountries[] = $wd->country;
                }
            }
        }

        if (!empty($syncCountries)) {
            app(WeatherService::class)->syncMultiple($syncCountries);
            
            // Refresh paginated collection with freshly synced database values
            $weatherData = $query
                ->orderByRaw('CASE WHEN temperature IS NULL THEN 1 ELSE 0 END, temperature DESC')
                ->paginate(20)
                ->withQueryString();
        }

        // Stats
        $totalMonitored  = Country::count();
        $extremeHeat     = WeatherData::where('temperature', '>', 35)->count();
        $stormRisk       = WeatherData::where('weather_status', 'Storm Risk')->count();
        $heavyRain       = WeatherData::where('weather_status', 'Heavy Rain')->count();
        $avgTemperature  = WeatherData::whereNotNull('temperature')->avg('temperature') ?: 0;

        $regions = Country::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view('weather.index', compact(
            'weatherData',
            'regions',
            'totalMonitored',
            'extremeHeat',
            'stormRisk',
            'heavyRain',
            'avgTemperature'
        ));
    }

}
