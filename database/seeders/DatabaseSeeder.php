<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FeatureSeeder::class,
            ClientTypeSeeder::class,
            VehicleUseSeeder::class,
            VehicleSeeder::class,
        ]);
    }
}
