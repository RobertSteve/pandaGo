<?php

namespace Database\Factories;

use App\Models\ClientType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientType>
 */
class ClientTypeFactory extends Factory
{
    public function definition(): array
    {

        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
