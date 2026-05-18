<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lot_id' => Lot::factory(),
            'buyer_id' => User::factory(),
            'seller_id' => User::factory(),
            'rating' => $this->faker->numberBetween(3, 5),
            'body' => $this->faker->sentence(12),
        ];
    }
}
