<?php

namespace Database\Factories;

use App\Models\VehicleUse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleUse>
 */
class VehicleUseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
        ];
    }
}
