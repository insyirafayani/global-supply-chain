<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class CurrencyService
{


    public function getCurrencyCode($countryName)
{

    try {


        $response = Http::timeout(30)
        ->get(
            'https://countriesnow.space/api/v0.1/countries/currency'
        );



        if(!$response->successful()){

            return null;

        }



        $countries =
        $response->json()['data'];



        foreach($countries as $item){


            if(
                strtolower($item['name'])
                ==
                strtolower($countryName)
            ){


                return $item['currency']
                ?? null;


            }


        }



        return null;



    }catch(\Exception $e){


        return null;


    }

}





    public function getCurrencyName($iso2)
    {


        try {


            $response = Http::timeout(30)
            ->get(
                "https://restcountries.com/v5.1/alpha/".$iso2
            );


            if(!$response->successful()){

                return null;

            }



            $data=$response->json();



            $code =
            array_key_first(
                $data['currencies']
            );



            return 
            $data['currencies']
            [$code]['name']
            ?? null;



        }catch(\Exception $e){


            return null;


        }


    }





    public function getCurrencyRate($currency)
    {


        try {


            $response = Http::timeout(30)
            ->get(
                "https://open.er-api.com/v6/latest/USD"
            );



            if(!$response->successful()){

                return null;

            }



            $data=$response->json();



            return
            $data['rates'][$currency]
            ?? null;



        }catch(\Exception $e){


            return null;


        }


    }



}