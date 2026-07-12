<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NewsCache;

class Country extends Model
{

protected $fillable = [

'name',
'official_name',
'iso2',
'iso3',
'capital',
'region',
'subregion',
'currency',
'currency_code',
'language',
'latitude',
'longitude',
'flag'

];



public function ports()
{
    return $this->hasMany(Port::class);
}



public function economicData()
{
    return $this->hasMany(EconomicData::class);
}



public function weatherData()
{
    return $this->hasMany(WeatherData::class);
}



public function currencyRates()
{
    return $this->hasMany(CurrencyRate::class);
}



public function news()
{
    return $this->hasMany(NewsCache::class);
}



public function riskScores()
{
    return $this->hasMany(RiskScore::class);
}



public function riskHistories()
{
    return $this->hasMany(RiskHistory::class);
}



public function watchlists()
{
    return $this->hasMany(Watchlist::class);
}



public function recommendations()
{
    return $this->hasMany(TradeRecommendation::class);
}


}