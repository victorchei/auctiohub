<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lot>
 */
class LotFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);
        $startingPrice = $this->faker->randomFloat(2, 50, 5000);
        $startsAt = $this->faker->dateTimeBetween('-7 days', '+1 day');
        $endsAt = (clone $startsAt)->modify('+'.$this->faker->numberBetween(3, 14).' days');

        return [
            'seller_id' => User::factory(),
            'category_id' => Category::factory(),
            'winner_id' => null,
            'title' => ucfirst($title),
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 99999),
            'description' => $this->faker->paragraphs(3, true),
            'starting_price' => $startingPrice,
            'current_price' => $startingPrice,
            'bid_increment' => $this->faker->randomElement([1, 5, 10, 25, 50, 100]),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'cover_image_path' => null,
        ];
    }

    public function ended(): static
    {
        return $this->state(fn () => [
            'status' => 'ended',
            'ends_at' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
