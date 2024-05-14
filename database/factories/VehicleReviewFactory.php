<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleReview>
 */
class VehicleReviewFactory extends Factory
{
    public function definition(): array
    {

        return [
            'vehicle_id' => Vehicle::factory(),
            'user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional()->sentence(),
        ];
    }
}
