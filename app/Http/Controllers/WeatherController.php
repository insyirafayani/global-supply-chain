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
    ) {

        $weather = $service->getWeather(
            $country->latitude,
            $country->longitude
        );

        if (!$weather) {
            return back()->with(
                'error',
                'Weather API failed to retrieve data'
            );
        }

        // Determine weather status
        $status = 'Normal';

        if ($weather['wind_speed'] > 50) {
            $status = 'Storm Risk';
        } elseif ($weather['rainfall'] > 50) {
            $status = 'Heavy Rain';
        } elseif ($weather['temperature'] > 35) {
            $status = 'Extreme Heat';
        } elseif ($weather['temperature'] < 0) {
            $status = 'Extreme Cold';
        }

        // FIX: Only use country_id as key — do NOT use recorded_at as key
        WeatherData::updateOrCreate(

            [
                'country_id' => $country->id,
            ],

            [
                'temperature'    => $weather['temperature'],
                'rainfall'       => $weather['rainfall'],
                'wind_speed'     => $weather['wind_speed'],
                'weather_status' => $status,
                'recorded_at'    => now(),
            ]

        );

        return back()->with(
            'success',
            'Weather data updated successfully'
        );

    }

}