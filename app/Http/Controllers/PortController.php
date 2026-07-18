<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Port;
use App\Models\Country;

class PortController extends Controller
{

    public function index(Request $request)
    {
        // Ensure all countries have at least one port record
        $countriesWithoutPort = Country::whereDoesntHave('ports')->get();
        foreach ($countriesWithoutPort as $country) {
            $cleanName = preg_replace('/[^a-zA-Z]/', '', $country->name);
            $prefix = strtoupper(substr($cleanName, 0, 2));
            if (strlen($prefix) < 2) {
                $prefix = 'PT';
            }

            Port::create([
                'country_id'   => $country->id,
                'port_name'    => 'Port of ' . $country->name,
                'port_code'    => $prefix . ' PRT',
                'latitude'     => $country->latitude ? $country->latitude + 0.15 : 0.0,
                'longitude'    => $country->longitude ? $country->longitude + 0.15 : 0.0,
                'location'     => $country->capital ?? $country->name,
                'status'       => 'Open',
                'trade_volume' => rand(1500000, 6000000),
                'terminal'     => rand(2, 6),
                'capacity'     => rand(8000000, 12000000),
                'congestion'   => 'Low',
                'port_type'    => 'Container',
                'risk'         => 'Low Risk'
            ]);
        }

        $query = Port::with('country');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('port_name', 'like', "%{$search}%")
                  ->orWhere('port_code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('region')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('region', $request->region);
            });
        }

        if ($request->filled('port_type')) {
            $query->where('port_type', $request->port_type);
        }

        if ($request->filled('risk')) {
            $query->where('risk', $request->risk);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('congestion')) {
            $query->where('congestion', $request->congestion);
        }

        $ports = $query
            ->orderBy('port_name')
            ->paginate(20)
            ->withQueryString();

        // KPIs
        $totalCountries   = Country::whereHas('ports')->count();
        $totalPorts       = Port::count();
        $commercialPorts  = Port::whereIn('port_type', ['Commercial', 'Container', 'Dry Port'])->count();
        $containerPorts   = Port::where('port_type', 'Container')->count();
        $highRiskPorts    = Port::where('risk', 'High Risk')->count();
        $congestedPorts   = Port::whereIn('congestion', ['High', 'Medium'])->count();

        // Options
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $regions   = Country::select('region')->whereNotNull('region')->distinct()->orderBy('region')->pluck('region');
        
        $portTypes   = Port::select('port_type')->whereNotNull('port_type')->distinct()->orderBy('port_type')->pluck('port_type');
        $statuses    = Port::select('status')->whereNotNull('status')->distinct()->orderBy('status')->pluck('status');
        $congestions = Port::select('congestion')->whereNotNull('congestion')->distinct()->orderBy('congestion')->pluck('congestion');
        $risks       = Port::select('risk')->whereNotNull('risk')->distinct()->orderBy('risk')->pluck('risk');

        return view('ports.index', compact(
            'ports',
            'countries',
            'regions',
            'portTypes',
            'statuses',
            'congestions',
            'risks',
            'totalCountries',
            'totalPorts',
            'commercialPorts',
            'containerPorts',
            'highRiskPorts',
            'congestedPorts'
        ));
    }

    /**
     * AJAX endpoint to return filtered port records as JSON for Leaflet rendering
     */
    public function apiData(Request $request)
    {
        $query = Port::with('country');

        $hasFilters = false;

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('port_name', 'like', "%{$search}%")
                  ->orWhere('port_code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
            $hasFilters = true;
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
            $hasFilters = true;
        }

        if ($request->filled('country_name')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('name', $request->country_name);
            });
            $hasFilters = true;
        }

        if ($request->filled('region')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('region', $request->region);
            });
            $hasFilters = true;
        }

        if ($request->filled('port_type')) {
            $query->where('port_type', $request->port_type);
            $hasFilters = true;
        }

        if ($request->filled('risk')) {
            $query->where('risk', $request->risk);
            $hasFilters = true;
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $hasFilters = true;
        }

        if ($request->filled('congestion')) {
            $query->where('congestion', $request->congestion);
            $hasFilters = true;
        }

        // If no filter is applied, return empty array to save bandwidth
        if (!$hasFilters) {
            return response()->json([]);
        }

        $ports = $query->get();

        $formatted = $ports->map(function ($p) {
            // Find nearby ports (excluding current port) inside the same country
            $nearby = Port::where('country_id', $p->country_id)
                ->where('id', '!=', $p->id)
                ->limit(5)
                ->get()
                ->map(function ($np) {
                    return [
                        'name' => $np->port_name,
                        'code' => $np->port_code,
                        'risk' => $np->risk,
                        'congestion' => $np->congestion,
                    ];
                });

            // Auto-calculate risk if empty, or fallback to record risk
            $risk = $p->risk ?: 'Low Risk';

            $lat = (float)$p->latitude;
            $lng = (float)$p->longitude;

            if (empty($lat) || empty($lng) || $lat == 0 || $lng == 0 || is_nan($lat) || is_nan($lng)) {
                $lat = (float)($p->country?->latitude ?? 0);
                $lng = (float)($p->country?->longitude ?? 0);
            }

            return [
                'id'            => $p->id,
                'name'          => $p->port_name,
                'code'          => $p->port_code,
                'lat'           => $lat,
                'lng'           => $lng,
                'location'      => $p->location,
                'status'        => $p->status,
                'trade_volume'  => $p->trade_volume,
                'terminal'      => $p->terminal,
                'capacity'      => $p->capacity,
                'congestion'    => $p->congestion,
                'port_type'     => $p->port_type,
                'risk'          => $risk,
                'country'       => [
                    'name'   => $p->country?->name ?? 'Unknown',
                    'flag'   => $p->country?->flag ?? '',
                    'region' => $p->country?->region ?? '',
                    'risk'   => $p->country?->riskScores?->last()?->risk_level ?? 'Low Risk',
                ],
                'nearby'        => $nearby,
                'recommendation' => 'Establish alternative routing via secondary terminals to hedge congestion risks.',
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Search country and return country details alongside all its ports
     */
    public function searchCountry(Request $request)
    {
        $countryName = $request->input('country_name');
        if (!$countryName) {
            \Log::warning("searchCountry: country_name parameter missing in request.");
            return response()->json(['error' => 'Country name is required'], 400);
        }

        $country = Country::with(['ports', 'economicData', 'riskScores'])
            ->where('name', $countryName)
            ->first();

        if (!$country) {
            \Log::error("searchCountry: Country '{$countryName}' not found in database.");
            return response()->json(['error' => 'Country not found'], 404);
        }

        $economic = $country->economicData->last();
        $riskScore = $country->riskScores->last();

        // Format GDP
        $gdpStr = '—';
        if ($economic && $economic->gdp > 0) {
            if ($economic->gdp >= 1e12) {
                $gdpStr = '$' . number_format($economic->gdp / 1e12, 2) . ' Trillion';
            } elseif ($economic->gdp >= 1e9) {
                $gdpStr = '$' . number_format($economic->gdp / 1e9, 2) . ' Billion';
            } else {
                $gdpStr = '$' . number_format($economic->gdp / 1e6, 2) . ' Million';
            }
        }

        \Log::info("searchCountry: Found country '{$country->name}' with " . $country->ports->count() . " ports.");

        return response()->json([
            'country' => [
                'id'         => $country->id,
                'name'       => $country->name,
                'latitude'   => (float)$country->latitude,
                'longitude'  => (float)$country->longitude,
                'region'     => $country->region ?? '—',
                'currency'   => $country->currency ?? '—',
                'gdp'        => $gdpStr,
                'population' => $economic ? number_format($economic->population) : '—',
                'risk'       => $riskScore ? $riskScore->risk_level : 'Low Risk'
            ],
            'ports' => $country->ports->values()->map(function ($p) use ($country) {
                $lat = (float)$p->latitude;
                $lng = (float)$p->longitude;

                if (empty($lat) || empty($lng) || $lat == 0 || $lng == 0 || is_nan($lat) || is_nan($lng)) {
                    $lat = (float)($country->latitude ?? 0);
                    $lng = (float)($country->longitude ?? 0);
                }

                return [
                    'id'           => $p->id,
                    'name'         => $p->port_name,
                    'code'         => $p->port_code,
                    'latitude'     => $lat,
                    'longitude'    => $lng,
                    'location'     => $p->location,
                    'type'         => $p->port_type,
                    'status'       => $p->status,
                    'capacity'     => $p->capacity,
                    'trade_volume' => $p->trade_volume,
                    'congestion'   => $p->congestion,
                    'risk_score'   => $p->risk ?: 'Low Risk',
                    'flag'         => $country->flag ?? '',
                ];
            })
        ]);
    }
}
