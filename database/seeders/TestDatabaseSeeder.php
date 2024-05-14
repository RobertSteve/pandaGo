<?php

namespace Database\Seeders;

use App\Models\ClientType;
use App\Models\Feature;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\VehiclePrice;
use App\Models\VehicleReview;
use App\Models\VehicleUse;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $features = Feature::factory(5)->create();
        $clientTypes = ClientType::factory(3)->create();
        $vehicleUses = VehicleUse::factory(5)->create();

        Vehicle::factory(9)
            ->has(VehicleImage::factory()->count(2), 'images')
            ->has(VehicleReview::factory()->count(3), 'reviews')
            ->has(VehiclePrice::factory()->count(5), 'prices')
            ->create()
            ->each(function (Vehicle $vehicle) use ($features, $clientTypes, $vehicleUses) {
                $vehicle->images()->save(VehicleImage::factory()->make([
                    'is_main' => true,
                ]));
                $selectedFeatures = $features->random(rand(1, 3));
                $selectedClientTypes = $clientTypes->random(rand(1, $clientTypes->count()));
                $selectedVehicleUses = $vehicleUses->random(rand(1, 3));
                $vehicle->features()->attach($selectedFeatures);
                $vehicle->clientTypes()->attach($selectedClientTypes);
                $vehicle->uses()->attach($selectedVehicleUses);
            });
    }
}
