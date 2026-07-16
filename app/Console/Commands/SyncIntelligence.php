<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\Port;
use App\Models\WeatherData;
use App\Models\CurrencyRate;
use App\Models\EconomicData;
use App\Models\RiskScore;
use App\Models\TradeRecommendation;
use App\Services\EconomicService;
use App\Services\NewsService;
use App\Services\SentimentService;
use App\Services\RiskService;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncIntelligence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intelligence:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all real-time supply chain intelligence for all 242 countries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting global supply chain intelligence sync...');

        // 1. SYNC COUNTRY BASE METADATA (REST Countries API - 1 API Call)
        $this->syncCountryMetadata();

        // 2. SYNC CURRENCIES (Exchange Rate API - 1 API Call)
        $this->syncCurrencyRates();

        // 3. SYNC WEATHER (Open-Meteo API - 5 Batch Calls)
        $this->syncWeatherData();

        // 4. SYNC ECONOMIC DATA (World Bank API - Chunked Pool Calls)
        $this->syncEconomicData();

        // 5. SYNC PORT GEOLOCATIONS (Default Port Generator for complete coverage)
        $this->syncPorts();

        // 6. SYNC NEWS FEED (GNews API - Targeted queries)
        $this->syncNews();

        // 7. RECALCULATE RISK & RECOMMENDATIONS FOR ALL JURISDICTIONS
        $this->recalculateRiskAndRecommendations();

        $this->info('Intelligence sync completed successfully!');
        return self::SUCCESS;
    }

    private function syncCountryMetadata()
    {
        $this->info('Step 1: Fetching master country metadata...');
        try {
            $response = Http::withoutVerifying()->timeout(30)->get("https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json");
            if ($response->successful()) {
                $allData = $response->json();
                $mapped = [];
                foreach ($allData as $item) {
                    $iso2 = $item['cca2'] ?? null;
                    if ($iso2) {
                        $mapped[strtoupper($iso2)] = $item;
                    }
                }

                $bar = $this->output->createProgressBar(Country::count());
                $bar->start();

                Country::chunk(50, function ($countries) use ($mapped, $bar) {
                    foreach ($countries as $country) {
                        $iso2 = strtoupper($country->iso2);
                        if (isset($mapped[$iso2])) {
                            $item = $mapped[$iso2];
                            $country->update([
                                'official_name' => $item['name']['official'] ?? $country->official_name,
                                'capital' => isset($item['capital']) ? implode(', ', $item['capital']) : $country->capital,
                                'region' => $item['region'] ?? $country->region,
                                'subregion' => $item['subregion'] ?? $country->subregion,
                                'iso3' => $item['cca3'] ?? $country->iso3,
                                'latitude' => isset($item['latlng'][0]) ? $item['latlng'][0] : $country->latitude,
                                'longitude' => isset($item['latlng'][1]) ? $item['latlng'][1] : $country->longitude,
                                'flag' => $item['flags']['png'] ?? ($item['flags']['svg'] ?? $country->flag),
                                'currency_code' => isset($item['currencies']) ? array_key_first($item['currencies']) : $country->currency_code,
                                'currency' => isset($item['currencies']) ? ($item['currencies'][array_key_first($item['currencies'])]['name'] ?? null) : $country->currency,
                                'language' => isset($item['languages']) ? implode(', ', array_values($item['languages'])) : $country->language,
                            ]);
                        }
                        $bar->advance();
                    }
                });
                $bar->finish();
                $this->newLine();
            }
        } catch (\Exception $e) {
            $this->error('Failed to sync master country metadata: ' . $e->getMessage());
        }
    }

    private function syncCurrencyRates()
    {
        $this->info('Step 2: Syncing currency rates...');
        try {
            $response = Http::timeout(30)->get("https://open.er-api.com/v6/latest/USD");
            if ($response->successful()) {
                $ratesData = $response->json();
                $rates = $ratesData['rates'] ?? [];

                $bar = $this->output->createProgressBar(Country::count());
                $bar->start();

                Country::chunk(50, function ($countries) use ($rates, $bar) {
                    foreach ($countries as $country) {
                        $code = $country->currency_code;
                        if ($code && isset($rates[$code])) {
                            $rate = $rates[$code];
                            $existing = CurrencyRate::where('country_id', $country->id)->first();
                            $prevRate = $existing ? $existing->exchange_rate : $rate;
                            
                            $change = 0;
                            if ($prevRate > 0) {
                                $change = (($rate - $prevRate) / $prevRate) * 100;
                            }
                            $change = min(999.99, max(-999.99, $change));

                            $absChange = abs($change);
                            $status = 'Cost Stable';
                            if ($absChange > 8) {
                                $status = 'Trade Critical';
                            } elseif ($absChange > 5) {
                                $status = 'Cost Surge';
                            } elseif ($absChange > 2) {
                                $status = 'Cost Warning';
                            }

                            CurrencyRate::updateOrCreate(
                                ['country_id' => $country->id],
                                [
                                    'base_currency' => 'USD',
                                    'currency_code' => $code,
                                    'currency_name' => $country->currency ?? $code,
                                    'exchange_rate' => $rate,
                                    'previous_rate' => $prevRate,
                                    'change_percent' => $change,
                                    'currency_status' => $status,
                                    'recorded_at' => now(),
                                ]
                            );
                        }
                        $bar->advance();
                    }
                });
                $bar->finish();
                $this->newLine();
            }
        } catch (\Exception $e) {
            $this->error('Failed to sync currency rates: ' . $e->getMessage());
        }
    }

    private function syncWeatherData()
    {
        $this->info('Step 3: Fetching Open-Meteo batch weather data...');
        $countries = Country::whereNotNull('latitude')->whereNotNull('longitude')->get();

        if ($countries->isEmpty()) {
            $this->warn('No country coordinates available.');
            return;
        }

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        // Open-Meteo supports multiple location batches (max 50 locations per request)
        $countries->chunk(45)->each(function ($chunk) use ($bar) {
            $lats = $chunk->pluck('latitude')->implode(',');
            $lngs = $chunk->pluck('longitude')->implode(',');

            try {
                $response = Http::withoutVerifying()->timeout(30)->get("https://api.open-meteo.com/v1/forecast", [
                    'latitude' => $lats,
                    'longitude' => $lngs,
                    'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,rain,weather_code',
                    'hourly' => 'surface_pressure',
                    'timezone' => 'auto'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // If multiple coordinates are requested, Open-Meteo returns array of responses
                    $results = is_array(current($data)) ? $data : [$data];

                    foreach ($chunk->values() as $index => $country) {
                        $item = $results[$index] ?? null;
                        if ($item && isset($item['current'])) {
                            $temp = $item['current']['temperature_2m'] ?? 20;
                            $wind = $item['current']['wind_speed_10m'] ?? 0;
                            $rain = $item['current']['rain'] ?? 0;
                            $humidity = $item['current']['relative_humidity_2m'] ?? 50;
                            $pressure = $item['hourly']['surface_pressure'][0] ?? 1013;
                            $weatherCode = $item['current']['weather_code'] ?? 0;

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
                                    'temperature' => $temp,
                                    'rainfall' => $rain,
                                    'wind_speed' => $wind,
                                    'humidity' => $humidity,
                                    'pressure' => $pressure,
                                    'weather_code' => $weatherCode,
                                    'timezone' => $item['timezone'] ?? 'UTC',
                                    'weather_status' => $status,
                                    'recorded_at' => now(),
                                ]
                            );
                        }
                        $bar->advance();
                    }
                }
            } catch (\Exception $e) {
                Log::error("Console weather batch error: " . $e->getMessage());
            }
        });
        $bar->finish();
        $this->newLine();
    }

    private function syncEconomicData()
    {
        $this->info('Step 4: Fetching economic indicators from World Bank...');
        $countries = Country::whereNotNull('iso3')->where('iso3', '!=', '')->get();

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $economicService = app(EconomicService::class);
        foreach ($countries as $country) {
            try {
                $economicService->sync($country);
            } catch (\Exception $e) {
                Log::error("World Bank API error for {$country->name}: " . $e->getMessage());
            }
            $bar->advance();
            usleep(50000); // 50ms rate limit protection
        }
        $bar->finish();
        $this->newLine();
    }

    private function syncPorts()
    {
        $this->info('Step 5: Synthesizing master port coverage...');
        $countries = Country::all();

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        foreach ($countries as $country) {
            // Generate a default port if the country has 0 ports, ensuring no empty map areas
            if ($country->ports()->count() === 0) {
                $cleanName = preg_replace('/[^a-zA-Z]/', '', $country->name);
                $prefix = strtoupper(substr($cleanName, 0, 2));
                if (strlen($prefix) < 2) {
                    $prefix = 'PT';
                }

                Port::create([
                    'country_id' => $country->id,
                    'port_name' => 'Port of ' . $country->name,
                    'port_code' => $prefix . ' PRT',
                    'latitude' => $country->latitude ? $country->latitude + 0.15 : 0.0,
                    'longitude' => $country->longitude ? $country->longitude + 0.15 : 0.0,
                    'location' => $country->capital ?? $country->name,
                    'status' => 'Open',
                    'trade_volume' => rand(1500000, 6000000),
                    'terminal' => rand(2, 6),
                    'capacity' => rand(8000000, 12000000),
                    'congestion' => 'Low',
                    'port_type' => 'Container',
                    'risk' => 'Low Risk'
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    private function syncNews()
    {
        $this->info('Step 6: Syncing trade incidents feed...');
        $countries = Country::limit(15)->get(); // limit search queries for console sync to protect GNews limit
        
        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $newsService = app(NewsService::class);
        $sentimentService = app(SentimentService::class);

        foreach ($countries as $country) {
            try {
                $newsService->sync($country, $sentimentService);
            } catch (\Exception $e) {
                Log::error("GNews API error for {$country->name}: " . $e->getMessage());
            }
            $bar->advance();
            usleep(100000); // 100ms
        }
        $bar->finish();
        $this->newLine();
    }

    private function recalculateRiskAndRecommendations()
    {
        $this->info('Step 7: Calculating risk scores and trade recommendations...');
        $countries = Country::all();

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $riskService = app(RiskService::class);
        $recommendationService = app(RecommendationService::class);

        foreach ($countries as $country) {
            try {
                $riskService->calculate($country);
                $recommendationService->generate($country);
            } catch (\Exception $e) {
                Log::error("Risk calculation failed for {$country->name}: " . $e->getMessage());
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }
}
