<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleImage>
 */
class VehicleImageFactory extends Factory
{
    public function definition(): array
    {

        return [
            'vehicle_id' => Vehicle::factory(),
            'image_path' => $this->faker->imageUrl(640, 480, 'transport'),
            'is_main' => false,
        ];
    }
}
