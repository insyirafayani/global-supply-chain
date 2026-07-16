<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{

    protected $fillable = [
        'country_id',
        'port_name',
        'port_code',
        'latitude',
        'longitude',
        'location',
        'status',
        'trade_volume',
        'terminal',
        'capacity',
        'congestion',
        'port_type',
        'risk',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
