<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $portsData = [
            [
                'country_name' => 'Indonesia',
                'port_name' => 'Port of Tanjung Priok',
                'port_code' => 'ID TPK',
                'latitude' => -6.1033,
                'longitude' => 106.8791,
                'location' => 'Jakarta, Java',
                'status' => 'Open',
                'trade_volume' => 7800000,
                'terminal' => 5,
                'capacity' => 10000000,
                'congestion' => 'Medium',
                'port_type' => 'Container',
                'risk' => 'Medium Risk',
            ],
            [
                'country_name' => 'Indonesia',
                'port_name' => 'Port of Tanjung Perak',
                'port_code' => 'ID TPR',
                'latitude' => -7.2023,
                'longitude' => 112.7371,
                'location' => 'Surabaya, East Java',
                'status' => 'Open',
                'trade_volume' => 3900000,
                'terminal' => 3,
                'capacity' => 5000000,
                'congestion' => 'Low',
                'port_type' => 'Container & Bulk',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'Singapore',
                'port_name' => 'Port of Singapore',
                'port_code' => 'SG SIN',
                'latitude' => 1.2644,
                'longitude' => 103.8400,
                'location' => 'Keppel, Singapore',
                'status' => 'Open',
                'trade_volume' => 37200000,
                'terminal' => 9,
                'capacity' => 45000000,
                'congestion' => 'Low',
                'port_type' => 'Container',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'China',
                'port_name' => 'Port of Shanghai',
                'port_code' => 'CN SHA',
                'latitude' => 30.6244,
                'longitude' => 122.0672,
                'location' => 'Yangshan, Shanghai',
                'status' => 'Open',
                'trade_volume' => 47300000,
                'terminal' => 12,
                'capacity' => 50000000,
                'congestion' => 'High',
                'port_type' => 'Container',
                'risk' => 'High Risk',
            ],
            [
                'country_name' => 'Netherlands',
                'port_name' => 'Port of Rotterdam',
                'port_code' => 'NL ROT',
                'latitude' => 51.9489,
                'longitude' => 4.1430,
                'location' => 'Maasvlakte, Rotterdam',
                'status' => 'Open',
                'trade_volume' => 14500000,
                'terminal' => 7,
                'capacity' => 18000000,
                'congestion' => 'Medium',
                'port_type' => 'Container & Oil',
                'risk' => 'Medium Risk',
            ],
            [
                'country_name' => 'Germany',
                'port_name' => 'Port of Hamburg',
                'port_code' => 'DE HAM',
                'latitude' => 53.5373,
                'longitude' => 9.9482,
                'location' => 'Elbe River, Hamburg',
                'status' => 'Open',
                'trade_volume' => 8700000,
                'terminal' => 4,
                'capacity' => 12000000,
                'congestion' => 'Medium',
                'port_type' => 'Container & General Cargo',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'United States',
                'port_name' => 'Port of Los Angeles',
                'port_code' => 'US LAX',
                'latitude' => 33.7288,
                'longitude' => -118.2620,
                'location' => 'San Pedro, California',
                'status' => 'Open',
                'trade_volume' => 10600000,
                'terminal' => 8,
                'capacity' => 15000000,
                'congestion' => 'High',
                'port_type' => 'Container',
                'risk' => 'High Risk',
            ],
            [
                'country_name' => 'Japan',
                'port_name' => 'Port of Tokyo',
                'port_code' => 'JP TYO',
                'latitude' => 35.6171,
                'longitude' => 139.7904,
                'location' => 'Tokyo Bay, Tokyo',
                'status' => 'Open',
                'trade_volume' => 4800000,
                'terminal' => 3,
                'capacity' => 6000000,
                'congestion' => 'Low',
                'port_type' => 'Container',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'Australia',
                'port_name' => 'Port of Sydney',
                'port_code' => 'AU SYD',
                'latitude' => -33.8568,
                'longitude' => 151.2153,
                'location' => 'Sydney Harbor, Sydney',
                'status' => 'Open',
                'trade_volume' => 2600000,
                'terminal' => 2,
                'capacity' => 4000000,
                'congestion' => 'Low',
                'port_type' => 'Container & Cruise',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'South Korea',
                'port_name' => 'Port of Busan',
                'port_code' => 'KR PUS',
                'latitude' => 35.1044,
                'longitude' => 129.0431,
                'location' => 'Busan Harbor, Busan',
                'status' => 'Open',
                'trade_volume' => 22000000,
                'terminal' => 6,
                'capacity' => 25000000,
                'congestion' => 'Medium',
                'port_type' => 'Container',
                'risk' => 'Medium Risk',
            ],
            [
                'country_name' => 'Belgium',
                'port_name' => 'Port of Antwerp',
                'port_code' => 'BE ANR',
                'latitude' => 51.2407,
                'longitude' => 4.3168,
                'location' => 'Scheldt River, Antwerp',
                'status' => 'Open',
                'trade_volume' => 12000000,
                'terminal' => 5,
                'capacity' => 15000000,
                'congestion' => 'Low',
                'port_type' => 'Container & Chemical',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'United Kingdom',
                'port_name' => 'Port of Felixstowe',
                'port_code' => 'GB FXT',
                'latitude' => 51.9566,
                'longitude' => 1.3090,
                'location' => 'Suffolk, Felixstowe',
                'status' => 'Open',
                'trade_volume' => 3800000,
                'terminal' => 2,
                'capacity' => 5000000,
                'congestion' => 'Medium',
                'port_type' => 'Container',
                'risk' => 'Medium Risk',
            ],
            [
                'country_name' => 'Brazil',
                'port_name' => 'Port of Santos',
                'port_code' => 'BR SSZ',
                'latitude' => -23.9669,
                'longitude' => -46.3283,
                'location' => 'Santos, São Paulo',
                'status' => 'Open',
                'trade_volume' => 4400000,
                'terminal' => 3,
                'capacity' => 6000000,
                'congestion' => 'High',
                'port_type' => 'Bulk & Container',
                'risk' => 'High Risk',
            ],
            [
                'country_name' => 'Canada',
                'port_name' => 'Port of Vancouver',
                'port_code' => 'CA VAN',
                'latitude' => 49.2888,
                'longitude' => -123.0789,
                'location' => 'Vancouver, British Columbia',
                'status' => 'Open',
                'trade_volume' => 3400000,
                'terminal' => 4,
                'capacity' => 5000000,
                'congestion' => 'Low',
                'port_type' => 'Container & Bulk',
                'risk' => 'Low Risk',
            ],
            [
                'country_name' => 'India',
                'port_name' => 'Nhava Sheva (JNPT)',
                'port_code' => 'IN NSA',
                'latitude' => 18.9500,
                'longitude' => 72.9500,
                'location' => 'Navi Mumbai, Maharashtra',
                'status' => 'Open',
                'trade_volume' => 5000000,
                'terminal' => 5,
                'capacity' => 7500000,
                'congestion' => 'High',
                'port_type' => 'Container',
                'risk' => 'High Risk',
            ],
            [
                'country_name' => 'South Africa',
                'port_name' => 'Port of Durban',
                'port_code' => 'ZA DUR',
                'latitude' => -29.8705,
                'longitude' => 31.0267,
                'location' => 'Durban, KwaZulu-Natal',
                'status' => 'Restricted',
                'trade_volume' => 2700000,
                'terminal' => 3,
                'capacity' => 4000000,
                'congestion' => 'High',
                'port_type' => 'Container & General',
                'risk' => 'High Risk',
            ],
        ];

        $count = 0;
        
        try {
            foreach ($portsData as $data) {
                // Find country dynamically
                $country = Country::where('name', 'like', '%' . $data['country_name'] . '%')->first();
                if ($country) {
                    unset($data['country_name']);
                    $data['country_id'] = $country->id;
                    
                    Port::updateOrCreate(
                        ['port_code' => $data['port_code']],
                        $data
                    );
                    $count++;
                } else {
                    $this->command->warn("Country '{$data['country_name']}' not found for port '{$data['port_name']}'.");
                }
            }

            $this->command->info("Inserted {$count} ports.");
        } catch (\Exception $e) {
            Log::error('Error seeding ports: ' . $e->getMessage());
            $this->command->error('Error seeding ports: ' . $e->getMessage());
        }
    }
}
