<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class CurrencyService
{


    public function getCurrencyCode($iso2)
    {


        try {


            $response = Http::timeout(30)
            ->get(
                "https://restcountries.com/v3.1/alpha/".$iso2
            );


            if(!$response->successful()){

                return null;

            }


            $data=$response->json();



            if(!isset($data[0]['currencies'])){

                return null;

            }



            return array_key_first(
                $data[0]['currencies']
            );



        } catch(\Exception $e){


            return null;


        }


    }




    public function getCurrencyRate($currency)
    {


        try{


            $response = Http::timeout(30)
            ->get(
                "https://open.er-api.com/v6/latest/USD"
            );



            if(!$response->successful()){

                return null;

            }



            $data=$response->json();



            return $data['rates'][$currency]
            ?? null;



        }catch(\Exception $e){


            return null;


        }


    }




    public function getCurrencyName($iso2)
    {


        try {


            $response = Http::timeout(30)
            ->get(
                "https://restcountries.com/v3.1/alpha/".$iso2
            );



            $data=$response->json();



            $currency =
            array_key_first(
                $data[0]['currencies']
            );



            return 
            $data[0]['currencies']
            [$currency]['name']
            ?? null;



        }catch(\Exception $e){


            return null;


        }


    }



    public function status($change)
    {


        if($change >= -2 && $change <=2){

            return "Cost Stable";

        }


        if($change >2 && $change <=5){

            return "Cost Warning";

        }


        if($change >5 && $change <=8){

            return "Cost Surge";

        }


        return "Trade Critical";


    }


}