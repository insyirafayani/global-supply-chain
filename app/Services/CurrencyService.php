<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\CurrencyRate;

class CurrencyService
{

    /*
    |--------------------------------------------------------------------------
    | GET CURRENCY CODE
    |--------------------------------------------------------------------------
    */

    public function getCurrencyCode($iso2)
    {

        try{

            $response = Http::timeout(30)
                ->get("https://restcountries.com/v3.1/alpha/".$iso2);

            if(!$response->successful()){

                return null;

            }

            $data = $response->json();

            if(empty($data) || !isset($data[0]['currencies'])){

                return null;

            }

            return array_key_first($data[0]['currencies']);

        }catch(\Exception $e){

            return null;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | GET CURRENCY NAME
    |--------------------------------------------------------------------------
    */

    public function getCurrencyName($iso2)
    {

        try{

            $response = Http::timeout(30)
                ->get("https://restcountries.com/v3.1/alpha/".$iso2);

            if(!$response->successful()){

                return null;

            }

            $data = $response->json();

            if(empty($data) || !isset($data[0]['currencies'])){

                return null;

            }

            $currency = array_key_first($data[0]['currencies']);

            return $data[0]['currencies'][$currency]['name'] ?? null;

        }catch(\Exception $e){

            return null;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | GET EXCHANGE RATE
    |--------------------------------------------------------------------------
    */

    public function getCurrencyRate($currency)
    {

        try{

            $response = Http::timeout(30)
                ->get("https://open.er-api.com/v6/latest/USD");

            if(!$response->successful()){

                return null;

            }

            $data = $response->json();

            return $data['rates'][$currency] ?? null;

        }catch(\Exception $e){

            return null;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function status($change)
    {
        $absChange = abs($change);
        if ($absChange <= 2) {
            return "Cost Stable";
        }
        if ($absChange <= 5) {
            return "Cost Warning";
        }
        if ($absChange <= 8) {
            return "Cost Surge";
        }
        return "Trade Critical";
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC DATABASE
    |--------------------------------------------------------------------------
    */

    public function sync(Country $country)
    {
        $code = $this->getCurrencyCode($country->iso2);

        if (!$code) {
            return null;
        }

        $rate = $this->getCurrencyRate($code);

        if (!$rate) {
            return null;
        }

        // Get previous rate from database to calculate real dynamic change
        $existing = CurrencyRate::where('country_id', $country->id)->first();
        $prevRate = $existing ? $existing->exchange_rate : $rate;
        
        $change = 0;
        if ($prevRate > 0) {
            $change = (($rate - $prevRate) / $prevRate) * 100;
        }
        $change = min(999.99, max(-999.99, $change));

        return CurrencyRate::updateOrCreate(
            [
                'country_id' => $country->id
            ],
            [
                'base_currency' => 'USD',
                'currency_code' => $code,
                'exchange_rate' => $rate,
                'previous_rate' => $prevRate,
                'change_percent' => $change,
                'currency_status' => $this->status($change),
                'recorded_at' => now()
            ]
        );
    }

}