<?php

namespace Database\Seeders;

use App\Models\VehicleUse;
use Illuminate\Database\Seeder;

class VehicleUseSeeder extends Seeder
{
    public function run(): void
    {
        VehicleUse::factory(5)->create();
    }
}
