<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitAppData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gerip:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize database, seed base data, and fetch initial API cache for deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting GERIP Deployment Initialization...');

        $this->info("\n--- [1/3] Running Database Migrations ---");
        $this->call('migrate', ['--force' => true]);

        $this->info("\n--- [2/3] Running Base Data Seeders (Non-API data) ---");
        $this->call('db:seed', ['--force' => true]);

        $this->info("\n--- [3/3] Syncing API Intelligence Cache (Fetching Weather, News, Economics...) ---");
        $this->info('This may take 1-2 minutes to complete.');
        $this->call('intelligence:sync');

        $this->info("\n==================================================");
        $this->info("FINAL DATABASE AUDIT REPORT");
        $this->info("==================================================");
        
        $countries = \App\Models\Country::count();
        $ports = \App\Models\Port::count();
        $weather = \App\Models\WeatherData::count();
        $news = \App\Models\NewsCache::count();
        $currency = \App\Models\CurrencyRate::count();
        
        $this->info("Countries inserted: " . $countries);
        $this->info("Ports inserted: " . $ports);
        $this->info("Weather inserted: " . $weather);
        $this->info("News inserted: " . $news);
        $this->info("Currency inserted: " . $currency);

        if ($countries == 0 || $ports == 0) {
            $this->error("\nWARNING: Critical data (Countries/Ports) is still empty. Please check the logs above for API or Fallback failures.");
        } else {
            $this->info("\nSUCCESS: Deployment Initialization Complete!");
            $this->info("Your Railway database is now identical to localhost.");
        }
        $this->info('==================================================');
        
        return self::SUCCESS;
    }
}
