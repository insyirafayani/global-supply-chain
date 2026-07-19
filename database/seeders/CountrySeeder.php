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
        $this->command->info('Country Seeder Started');
        
        $countries = [];
        $source = 'Local File (database/data/countries.json)';
        $filePath = database_path('data/countries.json');

        try {
            // 1. Cek apakah file master JSON ada, jika tidak ada, download dan simpan
            if (!File::exists($filePath)) {
                $this->command->info('Master file not found. Downloading from API...');
                
                // Buat folder jika belum ada
                if (!File::exists(dirname($filePath))) {
                    File::makeDirectory(dirname($filePath), 0755, true);
                }

                $response = Http::withoutVerifying()->timeout(30)->get("https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json");
                
                if ($response->successful()) {
                    File::put($filePath, $response->body());
                    $source = 'API (Downloaded to Local)';
                } else {
                    $this->command->error('Failed to download master data from API. Status: ' . $response->status());
                    return;
                }
            }

            // 2. Baca dari file lokal
            $jsonContent = File::get($filePath);
            $countries = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->command->error('JSON format error: ' . json_last_error_msg());
                return;
            }

        } catch (\Throwable $e) {
            $this->command->error('Error initializing data source: ' . $e->getMessage());
            Log::error('Country Seeder Error: ' . $e->getMessage());
            return;
        }

        if (!is_array($countries)) {
            $countries = [];
        }

        $receivedCount = count($countries);
        $this->command->info("Source: {$source}");
        $this->command->info("Total received: {$receivedCount}");
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

            $this->command->info("Total inserted: {$count}");
            $this->command->info("Inserted {$count} countries.");

        } catch (\Throwable $e) {
            Log::error('Error processing country data: ' . $e->getMessage());
            $this->command->error('Error processing country data: ' . $e->getMessage());
        }
    }
}