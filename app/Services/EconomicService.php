<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class EconomicService
{


    public function getEconomicData($countryCode)
    {


        $year = date('Y') - 1;


        $indicators = [

            'gdp'=>'NY.GDP.MKTP.CD',

            'inflation'=>'FP.CPI.TOTL.ZG',

            'population'=>'SP.POP.TOTL',

            'export'=>'TX.VAL.MRCH.CD.WT',

            'import'=>'TM.VAL.MRCH.CD.WT'


        ];



        $result = [];



        foreach($indicators as $key=>$indicator){



            $response = Http::timeout(30)
            ->get(

            "https://api.worldbank.org/v2/country/{$countryCode}/indicator/{$indicator}?format=json"

            );



            if($response->successful()){


                $data = $response->json();



                $value =
                $data[1][0]['value']
                ?? null;



                $result[$key]=$value;



            }


        }



        return $result;


    }


}