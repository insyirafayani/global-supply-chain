<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryIntelligenceService
{
    /**
     * static entry point to load country intelligence data
     */
    public static function load(Country $country)
    {
        return app(self::class)->loadCountryData($country);
    }

    /**
     * Checks database cache first, syncs with APIs if data is missing or older than 24 hours
     */
    public function loadCountryData(Country $country)
    {
        $risk = $country->riskScores()->first();

        // Sync if risk score doesn't exist, is older than 24 hours, metadata is missing, or economic data is empty
        if (!$risk || $risk->updated_at->lt(now()->subHours(24)) || empty($country->iso3) || empty($country->flag) || $country->economicData()->count() === 0) {
            $this->sync($country);
        }
    }

    /**
     * Centralized synchronization orchestrator using individual service try-catches
     */
    public function sync(Country $country)
    {
        // 1. Sync country metadata first (REST Countries API) to populate ISO3, flag, capital, coordinates, and currency code
        $this->syncCountryMetadata($country);

        // 2. Sync Weather (Open-Meteo API)
        try {
            app(WeatherService::class)->sync($country);
        } catch (\Exception $e) {
            Log::error("CountryIntel Weather Sync Error for {$country->name}: " . $e->getMessage());
        }

        // 3. Sync Economic Data (World Bank API)
        try {
            app(EconomicService::class)->sync($country);
        } catch (\Exception $e) {
            Log::error("CountryIntel Economic Sync Error for {$country->name}: " . $e->getMessage());
        }

        // 4. Sync Currency Data (Exchange Rate API)
        try {
            app(CurrencyService::class)->sync($country);
        } catch (\Exception $e) {
            Log::error("CountryIntel Currency Sync Error for {$country->name}: " . $e->getMessage());
        }

        // 5. Sync News Feed & Lexicon Sentiment Analysis (GNews API)
        try {
            app(NewsService::class)->sync($country, app(SentimentService::class));
        } catch (\Exception $e) {
            Log::error("CountryIntel News Sync Error for {$country->name}: " . $e->getMessage());
        }

        // 6. Recalculate Weighted Risk Model
        try {
            app(RiskService::class)->calculate($country);
        } catch (\Exception $e) {
            Log::error("CountryIntel Risk Engine Error for {$country->name}: " . $e->getMessage());
        }

        // 7. Generate Dynamic Recommendations
        try {
            app(RecommendationService::class)->generate($country);
        } catch (\Exception $e) {
            Log::error("CountryIntel Recommendation Engine Error for {$country->name}: " . $e->getMessage());
        }

        // Touch the country timestamp to record sync time
        $country->touch();
    }

    /**
     * Sync country metadata from stable GitHub countries dataset to ensure complete base records (ISO3, flag, coords, etc.)
     */
    public function syncCountryMetadata(Country $country)
    {
        try {
            $response = Http::withoutVerifying()->timeout(30)->get("https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json");

            if ($response->successful()) {
                $allCountries = $response->json();
                
                $item = null;
                $targetIso2 = strtoupper($country->iso2);
                foreach ($allCountries as $cData) {
                    if (isset($cData['cca2']) && strtoupper($cData['cca2']) === $targetIso2) {
                        $item = $cData;
                        break;
                    }
                }

                if ($item) {
                    $updateData = [];

                    // Official Name
                    if (isset($item['name']['official'])) {
                        $updateData['official_name'] = $item['name']['official'];
                    }

                    // Capital
                    if (isset($item['capital']) && is_array($item['capital'])) {
                        $updateData['capital'] = implode(', ', $item['capital']);
                    }

                    // Region & Subregion
                    if (isset($item['region'])) {
                        $updateData['region'] = $item['region'];
                    }
                    if (isset($item['subregion'])) {
                        $updateData['subregion'] = $item['subregion'];
                    }

                    // ISO3
                    if (isset($item['cca3'])) {
                        $updateData['iso3'] = $item['cca3'];
                    }

                    // Latitude & Longitude
                    if (isset($item['latlng']) && count($item['latlng']) >= 2) {
                        $updateData['latitude'] = $item['latlng'][0];
                        $updateData['longitude'] = $item['latlng'][1];
                    }

                    // Flags
                    if (isset($item['flags']['png'])) {
                        $updateData['flag'] = $item['flags']['png'];
                    } elseif (isset($item['flags']['svg'])) {
                        $updateData['flag'] = $item['flags']['svg'];
                    }

                    // Currency
                    if (isset($item['currencies']) && is_array($item['currencies'])) {
                        $currencyCode = array_key_first($item['currencies']);
                        $updateData['currency_code'] = $currencyCode;
                        $updateData['currency'] = $item['currencies'][$currencyCode]['name'] ?? null;
                    }

                    // Languages
                    if (isset($item['languages']) && is_array($item['languages'])) {
                        $updateData['language'] = implode(', ', array_values($item['languages']));
                    }

                    if (!empty($updateData)) {
                        $country->update($updateData);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("GitHub Countries JSON API Error for {$country->name}: " . $e->getMessage());
        }
    }
}