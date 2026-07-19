<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Fetching countries...');
        
        $countries = [];
        $source = 'API';

        try {
            // 1. Coba dari API yang valid (restcountries.com v3.1 sudah deprecated dan mengembalikan pesan error)
            $response = Http::withoutVerifying()->timeout(30)->get("https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json");
            
            $this->command->info('Response Status: ' . $response->status());
            Log::info('Country API Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $countries = $response->json();
                
                // Validasi tambahan: Pastikan data yang diterima adalah array of objects/arrays (bukan pesan error)
                if (isset($countries['success']) || (isset($countries[0]) && !isset($countries[0]['cca2']))) {
                    $this->command->warn('API mengembalikan pesan tidak terduga/error format.');
                    $countries = [];
                }
            } else {
                $this->command->warn('API failed. Response Body: ' . substr($response->body(), 0, 200));
                Log::warning('Country API Body: ' . $response->body());
            }
        } catch (\Throwable $e) {
            $this->command->error('API Request Error: ' . $e->getMessage());
            Log::error('Country API Error: ' . $e->getMessage());
        }

        // 2. Fallback otomatis ke File Lokal jika API gagal atau kosong
        if (empty($countries) || !is_array($countries)) {
            $this->command->warn('API Data is empty or invalid. Using local fallback...');
            $source = 'Fallback Local JSON';
            
            $fallbackPath = database_path('data/countries.json');
            if (File::exists($fallbackPath)) {
                $jsonContent = File::get($fallbackPath);
                $countries = json_decode($jsonContent, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->command->error('Fallback JSON format error: ' . json_last_error_msg());
                    $countries = [];
                }
            } else {
                $this->command->error('Fallback file not found: ' . $fallbackPath);
            }
        }

        if (!is_array($countries)) {
            $countries = [];
        }

        $receivedCount = count($countries);
        $this->command->info("Countries received: {$receivedCount}");
        
        if ($receivedCount === 0) {
            $this->command->error('No countries data available to insert. Aborting.');
            return;
        }

        // 3. Proses Insert
        $count = 0;
        try {
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
                if (!empty($data['currencies']) && is_array($data['currencies'])) {
                    $currency_code = array_key_first($data['currencies']);
                    $currency_name = $data['currencies'][$currency_code]['name'] ?? null;
                }

                $language = null;
                if (!empty($data['languages']) && is_array($data['languages'])) {
                    $language = array_values($data['languages'])[0] ?? null;
                }

                $latitude = $data['latlng'][0] ?? null;
                $longitude = $data['latlng'][1] ?? null;
                
                // Pengecekan flag agar lebih aman
                $flag = null;
                if (isset($data['flags'])) {
                    if (is_array($data['flags'])) {
                        $flag = $data['flags']['svg'] ?? ($data['flags']['png'] ?? null);
                    } elseif (is_string($data['flags'])) {
                        $flag = $data['flags'];
                    }
                }

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

        } catch (\Throwable $e) {
            Log::error('Error processing country data: ' . $e->getMessage());
            $this->command->error('Error processing country data: ' . $e->getMessage());
        }
    }
}