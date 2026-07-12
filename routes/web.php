<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\EconomicController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\NewsController;


Route::post(
'/countries/{country}/news-sync',
[NewsController::class,'sync']
)
->middleware('auth')
->name('news.sync');

Route::post(
'/countries/{country}/currency-sync',
[CurrencyController::class,'sync']
)
->middleware('auth')
->name('currency.sync');

Route::post(
'/countries/{country}/weather-sync',
[WeatherController::class,'sync']
)
->middleware('auth')
->name('weather.sync');

Route::post(
'/countries/{country}/economic-sync',
[EconomicController::class,'sync']
)
->middleware('auth')
->name('economic.sync');


Route::middleware(['auth'])->group(function(){


    Route::get('/map',
    [MapController::class,'index'])
    ->name('map.index');


});


Route::middleware(['auth'])->group(function(){


    Route::get('/countries',
    [CountryController::class,'index'])
    ->name('countries.index');



    Route::get('/countries/{id}',
    [CountryController::class,'show'])
    ->name('countries.show');


});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',
[DashboardController::class,'index'])
->middleware(['auth'])
->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function(){

    Route::get('/dashboard',
    [DashboardController::class,'index'])
    ->name('dashboard');

});

require __DIR__.'/auth.php';
