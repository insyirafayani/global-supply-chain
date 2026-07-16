<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Services\EconomicService;
use App\Services\CountryIntelligenceService;
use Illuminate\Support\Facades\Log;

class SyncEconomicData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'economic:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all economic indicators (GDP, Inflation, Population, Export, Import) from World Bank for all 242 countries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sync for all 242 countries economic data...');

        // Verify country metadata first to populate ISO3 codes
        try {
            $this->info('Verifying and syncing missing country metadata...');
            $countriesWithNoIso3 = Country::whereNull('iso3')->orWhere('iso3', '')->get();
            if ($countriesWithNoIso3->isNotEmpty()) {
                $intelService = app(CountryIntelligenceService::class);
                foreach ($countriesWithNoIso3 as $c) {
                    $intelService->syncCountryMetadata($c);
                }
            }
        } catch (\Exception $e) {
            Log::error("Economic Sync metadata pre-checks failed: " . $e->getMessage());
        }

        $countries = Country::whereNotNull('iso3')->where('iso3', '!=', '')->get();
        
        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $economicService = app(EconomicService::class);

        foreach ($countries as $country) {
            try {
                $economicService->sync($country);
            } catch (\Exception $e) {
                Log::error("Failed syncing economic data for {$country->name}: " . $e->getMessage());
            }

            $bar->advance();
            usleep(100000); // 100ms delay to protect API from rate limit limits
        }

        $bar->finish();
        $this->newLine();
        $this->info('Economic data synchronization completed successfully!');

        return self::SUCCESS;
    }
}
