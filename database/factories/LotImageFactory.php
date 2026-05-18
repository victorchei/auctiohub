<?php

namespace Database\Factories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LotImage>
 */
class LotImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lot_id' => Lot::factory(),
            'path' => 'lots/placeholder-'.$this->faker->numberBetween(1, 10).'.jpg',
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
