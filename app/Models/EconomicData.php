<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class EconomicData extends Model
{

protected $fillable=[

'country_id',
'year',
'gdp',
'inflation',
'population',
'export_value',
'import_value'

];


public function country()
{
    return $this->belongsTo(Country::class);
}

}