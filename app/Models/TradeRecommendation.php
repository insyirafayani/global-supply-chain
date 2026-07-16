<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TradeRecommendation extends Model
{


    protected $fillable = [

        'country_id',
        'recommendation',
        'priority',
        'reason',
        'action',
        'confidence'

    ];



    public function country()
    {

        return $this->belongsTo(
            Country::class
        );

    }


}