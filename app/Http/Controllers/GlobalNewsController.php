<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsCache;
use App\Models\Country;

class GlobalNewsController extends Controller
{

    public function index(Request $request)
    {

        $query = NewsCache::with('country')
            ->orderByDesc('created_at');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%");
            });
        }

        // Filter Country
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filter Sentiment
        if ($request->filled('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }

        $news = $query->paginate(15)->withQueryString();

        // Stats
        $totalNews     = NewsCache::count();
        $positiveNews  = NewsCache::where('sentiment', 'Positive')->count();
        $neutralNews   = NewsCache::where('sentiment', 'Neutral')->count();
        $negativeNews  = NewsCache::where('sentiment', 'Negative')->count();

        $countries = Country::orderBy('name')->pluck('name', 'id');

        return view('news.index', compact(
            'news',
            'countries',
            'totalNews',
            'positiveNews',
            'neutralNews',
            'negativeNews'
        ));

    }

}
