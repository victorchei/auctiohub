<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeLot(): Lot
    {
        $seller = User::factory()->create();
        $category = Category::factory()->create();

        return Lot::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'Policy lot',
            'slug' => 'policy-lot-'.uniqid(),
            'description' => 'desc',
            'starting_price' => 100,
            'current_price' => 100,
            'bid_increment' => 10,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDay(),
            'status' => 'active',
        ]);
    }

    public function test_seller_can_edit_lot_with_no_bids(): void
    {
        $lot = $this->makeLot();
        $this->assertTrue($lot->seller->can('update', $lot));
    }

    public function test_seller_cannot_edit_lot_with_bids(): void
    {
        $lot = $this->makeLot();
        Bid::create(['lot_id' => $lot->id, 'user_id' => User::factory()->create()->id, 'amount' => 110, 'placed_at' => now()]);

        $this->assertFalse($lot->seller->can('update', $lot));
    }

    public function test_other_user_cannot_edit_lot(): void
    {
        $lot = $this->makeLot();
        $other = User::factory()->create();

        $this->assertFalse($other->can('update', $lot));
    }

    public function test_admin_can_delete_any_lot_without_bids(): void
    {
        $lot = $this->makeLot();
        $admin = User::factory()->admin()->create();

        $this->assertTrue($admin->can('delete', $lot));
    }

    public function test_only_winner_can_create_review_on_ended_lot(): void
    {
        $lot = $this->makeLot();
        $winner = User::factory()->create();
        $lot->update(['status' => 'ended', 'winner_id' => $winner->id]);

        $other = User::factory()->create();

        $this->assertTrue($winner->can('create', [\App\Models\Review::class, $lot->fresh()]));
        $this->assertFalse($other->can('create', [\App\Models\Review::class, $lot->fresh()]));
    }
}
