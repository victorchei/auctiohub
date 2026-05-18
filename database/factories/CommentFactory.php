<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lot_id' => Lot::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'body' => $this->faker->paragraph(),
        ];
    }
}
