<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryComparisonController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $countryA = null;
        $countryB = null;

        if ($request->filled('country_a') && $request->filled('country_b')) {
            $countryA = Country::with([
                'economicData' => fn($q) => $q->latest()->limit(1),
                'weatherData' => fn($q) => $q->latest()->limit(1),
                'currencyRates' => fn($q) => $q->latest()->limit(1),
                'riskScores' => fn($q) => $q->latest()->limit(1),
                'recommendations' => fn($q) => $q->latest()->limit(1),
                'ports'
            ])->find($request->country_a);

            $countryB = Country::with([
                'economicData' => fn($q) => $q->latest()->limit(1),
                'weatherData' => fn($q) => $q->latest()->limit(1),
                'currencyRates' => fn($q) => $q->latest()->limit(1),
                'riskScores' => fn($q) => $q->latest()->limit(1),
                'recommendations' => fn($q) => $q->latest()->limit(1),
                'ports'
            ])->find($request->country_b);
        }

        return view('comparison.index', compact('countries', 'countryA', 'countryB'));
    }
}
