<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\EconomicController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WeatherMonitoringController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrencyIntelligenceController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GlobalNewsController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\CountryIntelligenceController;
use App\Http\Controllers\RiskAnalyticsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AdminController;


/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.index');
        }
        return redirect()->route('dashboard');
    }
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/api/dashboard/country/{id}', [DashboardController::class, 'countryData'])
        ->name('dashboard.countryData');

});


/*
|--------------------------------------------------------------------------
| Country Intelligence
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    // Country Monitor List
    Route::get('/countries', [CountryController::class, 'index'])
        ->name('countries.index');

    // Country Intelligence Center (auto-sync all data)
    Route::get('/countries/{id}', [CountryController::class, 'show'])
        ->name('countries.show');

    // Country sub-pages (for backward compatibility)
    Route::get('/countries/{id}/economic',       [CountryController::class, 'economic'])
        ->name('countries.economic');
    Route::get('/countries/{id}/weather',        [CountryController::class, 'weather'])
        ->name('countries.weather');
    Route::get('/countries/{id}/currency',       [CountryController::class, 'currency'])
        ->name('countries.currency');
    Route::get('/countries/{id}/news',           [CountryController::class, 'news'])
        ->name('countries.news');
    Route::get('/countries/{id}/recommendation', [CountryController::class, 'recommendation'])
        ->name('countries.recommendation');

});


/*
|--------------------------------------------------------------------------
| Country Data Sync Actions (POST)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::post('/countries/{country}/risk-calculate',
        [RiskController::class, 'calculate'])->name('risk.calculate');

    Route::post('/countries/{country}/news-sync',
        [NewsController::class, 'sync'])->name('news.sync');

    Route::post('/countries/{country}/currency-sync',
        [CurrencyController::class, 'sync'])->name('currency.sync');

    Route::post('/countries/{country}/weather-sync',
        [WeatherController::class, 'sync'])->name('weather.sync');

    Route::post('/countries/{country}/economic-sync',
        [EconomicController::class, 'sync'])->name('economic.sync');

    Route::post('/countries/{country}/generate',
        [CountryIntelligenceController::class, 'generate'])->name('country.generate');

});


/*
|--------------------------------------------------------------------------
| Risk Analytics (Global Module)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/risk-analytics', [RiskAnalyticsController::class, 'index'])
        ->name('risk.analytics');

    Route::get('/risk-analytics/api', [RiskAnalyticsController::class, 'apiData'])
        ->name('risk.analytics.api');

    Route::post('/risk-analytics/refresh', [RiskAnalyticsController::class, 'refreshAll'])
        ->name('risk.analytics.refresh');

});


/*
|--------------------------------------------------------------------------
| Weather Monitoring (Global Module)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/weather-monitoring', [WeatherMonitoringController::class, 'index'])
        ->name('weather.index');

});


/*
|--------------------------------------------------------------------------
| Currency Intelligence (Global Module)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/currency-intelligence', [CurrencyIntelligenceController::class, 'index'])
        ->name('currency.index');

});


/*
|--------------------------------------------------------------------------
| Global News (Global Module)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/global-news', [GlobalNewsController::class, 'index'])
        ->name('news.index');

});


/*
|--------------------------------------------------------------------------
| Port Monitoring (Global Module)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/port-monitoring', [PortController::class, 'index'])
        ->name('ports.index');

    Route::get('/port-monitoring/data', [PortController::class, 'apiData'])
        ->name('ports.apiData');

    Route::get('/api/ports/search-country', [PortController::class, 'searchCountry'])
        ->name('ports.searchCountry');

});


/*
|--------------------------------------------------------------------------
| Watchlist
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/watchlist',                   [WatchlistController::class, 'index'])
        ->name('watchlist.index');
    Route::post('/watchlist',                  [WatchlistController::class, 'store'])
        ->name('watchlist.store');
    Route::delete('/watchlist/{watchlist}',    [WatchlistController::class, 'destroy'])
        ->name('watchlist.destroy');

});


/*
|--------------------------------------------------------------------------
| Global Map (Port / Legacy)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsUser::class])->group(function () {

    Route::get('/map', [MapController::class, 'index'])
        ->name('map.index');

    // Data Visualization (Analytics)
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])
        ->name('analytics.index');

    // Country Comparison
    Route::get('/country-comparison', [\App\Http\Controllers\CountryComparisonController::class, 'index'])
        ->name('comparison.index');

});


/*
|--------------------------------------------------------------------------
| Admin Panel (Admin Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/',          [AdminController::class, 'index'])
            ->name('index');
        Route::get('/users',     [AdminController::class, 'users'])
            ->name('users');
        Route::post('/users/{user}/toggle-role', [AdminController::class, 'toggleRole'])
            ->name('users.toggle-role');
        Route::get('/ports', [AdminController::class, 'ports'])
            ->name('ports');
        Route::get('/articles', [AdminController::class, 'articles'])
            ->name('articles');

    });


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile',    [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__ . '/auth.php';
