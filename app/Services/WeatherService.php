<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class WeatherService
{

    public function getWeather($latitude, $longitude)
    {


        try {


            $response = Http::withoutVerifying()
                ->connectTimeout(20)
                ->timeout(60)
                ->retry(3, 2000)
                ->get(
                    'https://api.open-meteo.com/v1/forecast',
                    [

                        'latitude'=>$latitude,

                        'longitude'=>$longitude,


                        'current_weather'=>true,


                        'hourly'=>[
                            'rain'
                        ],


                        'timezone'=>'auto'

                    ]
                );



            if(!$response->successful()){

                return null;

            }



            $data=$response->json();



            return [

                'temperature'=>
                $data['current_weather']['temperature']
                ?? null,


                'rainfall'=>
                $data['hourly']['rain'][0]
                ?? 0,


                'wind_speed'=>
                $data['current_weather']['windspeed']
                ?? 0,


            ];



        } catch(\Exception $e){


            \Log::error(
                'Weather API Error : '.$e->getMessage()
            );


            return null;


        }


    }


}