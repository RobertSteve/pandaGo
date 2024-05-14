<?php

namespace Database\Seeders;

use App\Models\ClientType;
use App\Models\Feature;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\VehiclePrice;
use App\Models\VehicleReview;
use App\Models\VehicleUse;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $features = Feature::all();
        $clientTypes = ClientType::all();
        $vehicleUses = VehicleUse::all();

        Vehicle::factory(50)
            ->has(VehicleImage::factory()->count(2), 'images')
            ->has(VehicleReview::factory()->count(3), 'reviews')
            ->has(VehiclePrice::factory()->count(5), 'prices')
            ->create()
            ->each(function (Vehicle $vehicle) use ($features, $clientTypes, $vehicleUses) {
                $vehicle->images()->first()->update(['is_main' => true]);
                $vehicle->features()->attach($features->random(rand(1, 3)));
                $vehicle->clientTypes()->attach($clientTypes->random(rand(1, $clientTypes->count())));
                $vehicle->uses()->attach($vehicleUses->random(rand(1, 3)));
            });
    }
}
