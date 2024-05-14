<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    public function definition(): array
    {

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'model_id' => VehicleModel::factory(),
        ];
    }
}
