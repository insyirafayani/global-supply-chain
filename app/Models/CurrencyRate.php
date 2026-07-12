<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CurrencyRate extends Model
{

protected $fillable=[

'country_id',
'base_currency',
'currency_code',
'exchange_rate',
'previous_rate',
'change_percent',
'currency_status',
'recorded_at'

];


public function country()
{
    return $this->belongsTo(Country::class);
}

}