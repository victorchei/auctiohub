<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lot_id' => Lot::factory(),
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'placed_at' => $this->faker->dateTimeBetween('-14 days', 'now'),
        ];
    }
}
