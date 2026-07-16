<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use App\Models\WeatherData;
use App\Models\CurrencyRate;
use App\Models\EconomicData;
use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use App\Models\Watchlist;

class AdminController extends Controller
{

    /**
     * Admin Dashboard Overview
     */
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_countries'  => Country::count(),
            'total_ports'      => Port::count(),
            'total_articles'   => Article::count(),
            'total_watchlist'  => Watchlist::count(),
        ];

        return view('admin.index', compact('stats'));
    }

    /**
     * Manage Users
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Toggle user role (admin/user)
     */
    public function toggleRole(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot change your own role.');
        }

        $user->update([
            'role' => $user->role === 'admin' ? 'user' : 'admin',
        ]);

        return back()->with('success', "Role for {$user->name} updated to {$user->role}.");
    }

    /**
     * Port Dataset management
     */
    public function ports(Request $request)
    {
        $query = Port::with('country');

        if ($request->filled('search')) {
            $query->where('port_name', 'like', '%' . $request->search . '%')
                  ->orWhere('port_code', 'like', '%' . $request->search . '%');
        }

        $ports = $query->orderBy('port_name')->paginate(20)->withQueryString();

        return view('admin.ports', compact('ports'));
    }

    /**
     * Analysis Articles management
     */
    public function articles(Request $request)
    {
        $query = Article::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $articles = $query->latest()->paginate(20)->withQueryString();

        return view('admin.articles', compact('articles'));
    }

}
