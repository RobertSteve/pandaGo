<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehiclePrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehiclePrice>
 */
class VehiclePriceFactory extends Factory
{
    public function definition(): array
    {

        return [
            'vehicle_id' => Vehicle::factory(),
            'contract_length_months' => $this->faker->randomElement([12, 24, 36, 48, 60]),
            'price' => $this->faker->randomFloat(2, 50, 300),
            'range_km' => $this->faker->numberBetween(30, 300),
        ];
    }
}
