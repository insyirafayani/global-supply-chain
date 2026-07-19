<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Fetching countries from API...');

        try {
            $response = Http::timeout(30)->get("https://restcountries.com/v3.1/all");

            if (!$response->successful()) {
                $errorMsg = 'Failed to fetch countries from API. Status: ' . $response->status();
                Log::error($errorMsg);
                $this->command->error($errorMsg);
                return;
            }

            $countries = $response->json();
            $count = 0;

            foreach ($countries as $data) {
                $iso2 = $data['cca2'] ?? null;
                $name = $data['name']['common'] ?? null;
                
                if (!$iso2 || !$name) continue;

                $official_name = $data['name']['official'] ?? $name;
                $iso3 = $data['cca3'] ?? null;
                $capital = isset($data['capital'][0]) ? $data['capital'][0] : null;
                $region = $data['region'] ?? null;
                $subregion = $data['subregion'] ?? null;
                
                $currency_code = null;
                $currency_name = null;
                if (!empty($data['currencies'])) {
                    $currency_code = array_key_first($data['currencies']);
                    $currency_name = $data['currencies'][$currency_code]['name'] ?? null;
                }

                $language = null;
                if (!empty($data['languages'])) {
                    $language = array_values($data['languages'])[0] ?? null;
                }

                $latitude = $data['latlng'][0] ?? null;
                $longitude = $data['latlng'][1] ?? null;
                $flag = $data['flags']['svg'] ?? ($data['flags']['png'] ?? null);

                Country::updateOrCreate(
                    ['iso2' => $iso2],
                    [
                        'name' => $name,
                        'official_name' => $official_name,
                        'iso3' => $iso3,
                        'capital' => $capital,
                        'region' => $region,
                        'subregion' => $subregion,
                        'currency' => $currency_name,
                        'currency_code' => $currency_code,
                        'language' => $language,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'flag' => $flag
                    ]
                );

                $count++;
            }

            $this->command->info("Inserted {$count} countries.");

        } catch (\Exception $e) {
            Log::error('Error seeding countries: ' . $e->getMessage());
            $this->command->error('Error seeding countries: ' . $e->getMessage());
        }
    }
}