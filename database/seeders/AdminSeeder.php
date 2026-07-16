<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = 'admin@gmail.com';

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]);
        }
    }
}
