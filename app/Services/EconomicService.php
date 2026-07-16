<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\EconomicData;
use Illuminate\Support\Facades\Log;

class EconomicService
{
    /**
     * Fetch economic data from World Bank API in parallel
     */
    public function getEconomicData($countryCode)
    {
        if (empty($countryCode)) {
            Log::error("Skipped World Bank fetch: Country ISO3 code is empty.");
            return null;
        }

        $apiUrlBase = "https://api.worldbank.org/v2/country/{$countryCode}/indicator";

        try {
            Log::info("Calling World Bank Economic API for: {$countryCode}");

            $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($apiUrlBase) {
                return [
                    $pool->as('gdp')->timeout(15)->get("{$apiUrlBase}/NY.GDP.MKTP.CD?format=json&per_page=10"),
                    $pool->as('inflation')->timeout(15)->get("{$apiUrlBase}/FP.CPI.TOTL.ZG?format=json&per_page=10"),
                    $pool->as('population')->timeout(15)->get("{$apiUrlBase}/SP.POP.TOTL?format=json&per_page=10"),
                    $pool->as('export')->timeout(15)->get("{$apiUrlBase}/TX.VAL.MRCH.CD.WT?format=json&per_page=10"),
                    $pool->as('import')->timeout(15)->get("{$apiUrlBase}/TM.VAL.MRCH.CD.WT?format=json&per_page=10"),
                ];
            });

            $result = [];
            $years = [];

            foreach (['gdp', 'inflation', 'population', 'export', 'import'] as $key) {
                if (isset($responses[$key]) && $responses[$key] instanceof \Illuminate\Http\Client\Response && $responses[$key]->successful()) {
                    $json = $responses[$key]->json();
                    $parsed = $this->parseFirstNonNullValue($json);
                    
                    if ($parsed) {
                        $result[$key] = $parsed['value'];
                        $years[]      = $parsed['year'];
                    } else {
                        $result[$key] = null;
                        Log::warning("World Bank indicator [{$key}] for {$countryCode} returned empty or null values.");
                    }
                } else {
                    $result[$key] = null;
                    $status = (isset($responses[$key]) && $responses[$key] instanceof \Illuminate\Http\Client\Response) ? $responses[$key]->status() : 'Timeout/Connection Error';
                    Log::error("World Bank API call for indicator [{$key}] failed with status: {$status}");
                }
            }

            $result['year'] = !empty($years) ? max($years) : (int)(date('Y') - 1);
            
            Log::info("Parsed Economic Results for {$countryCode}: GDP={$result['gdp']}, Pop={$result['population']}, Inflation={$result['inflation']}, Year={$result['year']}");
            return $result;

        } catch(\Exception $e) {
            Log::error('World Bank Economic API pool error: ' . $e->getMessage());
            return [
                'gdp'        => null,
                'inflation'  => null,
                'population' => null,
                'export'     => null,
                'import'     => null,
                'year'       => (int)(date('Y') - 1),
            ];
        }
    }

    /**
     * Sync economic data with database
     */
    public function sync(Country $country)
    {
        if (empty($country->iso3)) {
            // Attempt to resolve metadata (like ISO3) first
            try {
                app(CountryIntelligenceService::class)->syncCountryMetadata($country);
            } catch (\Exception $e) {
                Log::error("Failed to auto-resolve metadata for {$country->name}: " . $e->getMessage());
            }
        }

        if (empty($country->iso3)) {
            Log::error("Cannot sync economic data: {$country->name} has no ISO3 country code.");
            return null;
        }

        $economic = $this->getEconomicData($country->iso3);

        if (!$economic) {
            return null;
        }

        $record = EconomicData::updateOrCreate(
            [
                'country_id' => $country->id,
                'year'       => $economic['year'] ?? (date('Y') - 1)
            ],
            [
                'gdp'          => $economic['gdp'] ?? 0,
                'inflation'    => $economic['inflation'] ?? 0,
                'population'   => $economic['population'] ?? 0,
                'export_value' => $economic['export'] ?? 0,
                'import_value' => $economic['import'] ?? 0
            ]
        );

        Log::info("Database updated economic data for {$country->name} for year {$economic['year']}.");
        return $record;
    }

    /**
     * Parse first non-null historical value from World Bank array
     */
    private function parseFirstNonNullValue($json)
    {
        if (isset($json[1]) && is_array($json[1])) {
            foreach ($json[1] as $item) {
                if (isset($item['value']) && $item['value'] !== null) {
                    return [
                        'value' => $item['value'],
                        'year'  => (int)($item['date'] ?? date('Y') - 1)
                    ];
                }
            }
        }
        return null;
    }
}