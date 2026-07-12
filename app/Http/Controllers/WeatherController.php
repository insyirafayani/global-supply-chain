<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\WeatherData;
use App\Services\WeatherService;



class WeatherController extends Controller
{


    public function sync(
        Country $country,
        WeatherService $service
    )
    {



        $weather =
        $service->getWeather(

            $country->latitude,

            $country->longitude

        );



        if(!$weather){


            return back()
            ->with(
                'error',
                'Weather API failed'
            );


        }




        $status = "Normal";



        if($weather['rainfall'] > 50){

            $status="Heavy Rain";

        }


        if($weather['wind_speed'] > 50){

            $status="Storm Risk";

        }




        WeatherData::updateOrCreate(

        [

            'country_id'=>$country->id,


            'recorded_at'=>now()

        ],


        [


            'temperature'=>
            $weather['temperature'],


            'rainfall'=>
            $weather['rainfall'],


            'wind_speed'=>
            $weather['wind_speed'],


            'weather_status'=>
            $status


        ]


        );



        return back()
        ->with(
            'success',
            'Weather updated'
        );


    }



}