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
        $this->info('Deployment Initialization Complete!');
        $this->info('Your Railway database is now fully populated.');
        $this->info('==================================================');
        
        return self::SUCCESS;
    }
}
