<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\CurrencyService;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyService = new CurrencyService();

        // 
        // Logika seeding country tidak diubah.
        // 
    }
}