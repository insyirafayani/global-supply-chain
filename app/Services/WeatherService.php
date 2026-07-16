<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\WeatherData;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Get weather data for a single country coordinates
     */
    public function getWeather($latitude, $longitude)
    {
        if ($latitude === null || $longitude === null) {
            Log::warning("Skipped weather fetch: Coordinates are null (Lat: " . ($latitude ?? 'null') . ", Lng: " . ($longitude ?? 'null') . ")");
            return null;
        }

        $apiUrl = 'https://api.open-meteo.com/v1/forecast';
        $params = [
            'latitude'        => $latitude,
            'longitude'       => $longitude,
            'current_weather' => 'true',
            'hourly'          => 'rain',
            'timezone'        => 'auto'
        ];

        try {
            Log::info("Calling Weather API: " . $apiUrl . '?' . http_build_query($params));

            $response = Http::withoutVerifying()
                ->connectTimeout(15)
                ->timeout(30)
                ->retry(3, 1000)
                ->get($apiUrl, $params);

            Log::info("Weather API Response Status: " . $response->status());

            if (!$response->successful()) {
                Log::error("Weather API request failed with status: " . $response->status() . " and body: " . $response->body());
                return null;
            }

            $data = $response->json();

            return [
                'temperature'  => $data['current_weather']['temperature'] ?? null,
                'rainfall'     => $data['hourly']['rain'][0] ?? 0,
                'wind_speed'   => $data['current_weather']['windspeed'] ?? 0,
                'weather_code' => $data['current_weather']['weathercode'] ?? null,
                'timezone'     => $data['timezone'] ?? null,
            ];

        } catch(\Exception $e) {
            Log::error('Weather API Error : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync weather data for a single country
     */
    public function sync(Country $country)
    {
        $weather = $this->getWeather($country->latitude, $country->longitude);

        if (!$weather) {
            return null;
        }

        $status = 'Normal';
        if ($weather['wind_speed'] > 60 || $weather['temperature'] > 38 || $weather['temperature'] < -5) {
            $status = 'Extreme';
        } elseif ($weather['wind_speed'] > 40) {
            $status = 'Storm Risk';
        } elseif ($weather['rainfall'] > 15) {
            $status = 'Heavy Rain';
        }

        $record = WeatherData::updateOrCreate(
            [
                'country_id' => $country->id
            ],
            [
                'temperature'    => $weather['temperature'],
                'rainfall'       => $weather['rainfall'],
                'wind_speed'     => $weather['wind_speed'],
                'weather_code'   => $weather['weather_code'],
                'timezone'       => $weather['timezone'],
                'weather_status' => $status,
                'recorded_at'    => now(),
            ]
        );

        Log::info("Database updated weather for country via sync: {$country->name} (Temp: {$weather['temperature']})");
        return $record;
    }

    /**
     * Batch sync weather data for multiple countries to optimize loading time
     */
    public function syncMultiple($countries)
    {
        $validCountries = [];
        $lats = [];
        $lngs = [];

        foreach ($countries as $country) {
            if ($country->latitude === null || $country->longitude === null) {
                Log::warning("Weather batch sync skipped for {$country->name}: Coordinates are null");
                continue;
            }
            $validCountries[] = $country;
            $lats[] = $country->latitude;
            $lngs[] = $country->longitude;
        }

        if (empty($validCountries)) {
            return;
        }

        $latsStr = implode(',', $lats);
        $lngsStr = implode(',', $lngs);

        $apiUrl = 'https://api.open-meteo.com/v1/forecast';
        $params = [
            'latitude'        => $latsStr,
            'longitude'       => $lngsStr,
            'current_weather' => 'true',
            'hourly'          => 'rain',
            'timezone'        => 'auto'
        ];

        try {
            Log::info("Calling Weather Batch API: " . $apiUrl . '?' . http_build_query($params));

            $response = Http::withoutVerifying()
                ->connectTimeout(15)
                ->timeout(30)
                ->retry(3, 1000)
                ->get($apiUrl, $params);

            Log::info("Weather Batch API Response Status: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                
                // If only 1 country was requested, Open-Meteo returns single object instead of array of objects
                $results = is_array(current($data)) ? $data : [$data];

                foreach ($validCountries as $index => $country) {
                    $item = $results[$index] ?? null;
                    if ($item && isset($item['current_weather'])) {
                        $temp = $item['current_weather']['temperature'] ?? null;
                        $wind = $item['current_weather']['windspeed'] ?? 0;
                        $rain = $item['hourly']['rain'][0] ?? 0;
                        $weatherCode = $item['current_weather']['weathercode'] ?? 0;

                        $status = 'Normal';
                        if ($wind > 60 || $temp > 38 || $temp < -5) {
                            $status = 'Extreme';
                        } elseif ($wind > 40) {
                            $status = 'Storm Risk';
                        } elseif ($rain > 15) {
                            $status = 'Heavy Rain';
                        }

                        WeatherData::updateOrCreate(
                            ['country_id' => $country->id],
                            [
                                'temperature'    => $temp,
                                'rainfall'       => $rain,
                                'wind_speed'     => $wind,
                                'weather_code'   => $weatherCode,
                                'timezone'       => $item['timezone'] ?? 'UTC',
                                'weather_status' => $status,
                                'recorded_at'    => now(),
                            ]
                        );
                        Log::info("Database updated weather for country: {$country->name} (Temp: {$temp}, Rain: {$rain}, Wind: {$wind})");
                    } else {
                        Log::warning("No weather structure in response item for {$country->name}");
                    }
                }
            } else {
                Log::error("Weather Batch API error: Status " . $response->status() . " - " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Weather Batch API Exception: " . $e->getMessage());
        }
    }
}