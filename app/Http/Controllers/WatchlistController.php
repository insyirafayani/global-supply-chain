<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use App\Models\Country;
use App\Models\RiskScore;

class WatchlistController extends Controller
{

    public function index()
    {
        $watchlists = Watchlist::where('user_id', auth()->id())
            ->with(['country.riskScores', 'country.weatherData', 'country.currencyRates'])
            ->orderBy('created_at', 'desc')
            ->get();

        $countries = Country::orderBy('name')->pluck('name', 'id');

        return view('watchlist.index', compact('watchlists', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        // Prevent duplicate
        $exists = Watchlist::where('user_id', auth()->id())
            ->where('country_id', $request->country_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Country is already in your watchlist.');
        }

        Watchlist::create([
            'user_id'    => auth()->id(),
            'country_id' => $request->country_id,
        ]);

        return back()->with('success', 'Country added to watchlist.');
    }

    public function destroy(Watchlist $watchlist)
    {
        // Ensure user owns this watchlist entry
        if ($watchlist->user_id !== auth()->id()) {
            abort(403);
        }

        $watchlist->delete();

        return back()->with('success', 'Country removed from watchlist.');
    }

}
