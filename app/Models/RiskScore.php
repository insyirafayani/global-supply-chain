<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{

    protected $fillable = [

        'country_id',
        'weather_risk',
        'inflation_risk',
        'news_risk',
        'currency_risk',
        'total_score',
        'risk_level'

    ];



    public function country()
    {
        return $this->belongsTo(
            Country::class
        );
    }


}