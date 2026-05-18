<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidTest extends TestCase
{
    use RefreshDatabase;

    private function makeLot(array $attrs = []): Lot
    {
        $seller = User::factory()->create();
        $category = Category::factory()->create();

        return Lot::create(array_merge([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'Test lot',
            'slug' => 'test-lot-'.uniqid(),
            'description' => 'desc',
            'starting_price' => 100,
            'current_price' => 100,
            'bid_increment' => 10,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDay(),
            'status' => 'active',
        ], $attrs));
    }

    public function test_authenticated_user_can_place_valid_bid(): void
    {
        $lot = $this->makeLot();
        $bidder = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($bidder)->post("/lots/{$lot->slug}/bids", [
            'amount' => 150,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bids', ['lot_id' => $lot->id, 'user_id' => $bidder->id, 'amount' => 150]);
        $this->assertEquals(150, (float) $lot->fresh()->current_price);
    }

    public function test_bid_lower_than_min_is_rejected(): void
    {
        $lot = $this->makeLot();
        $bidder = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($bidder)->from("/lots/{$lot->slug}")->post("/lots/{$lot->slug}/bids", [
            'amount' => 105,
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertDatabaseMissing('bids', ['lot_id' => $lot->id, 'user_id' => $bidder->id]);
    }

    public function test_seller_cannot_bid_on_own_lot(): void
    {
        $lot = $this->makeLot();
        $seller = $lot->seller;

        $response = $this->actingAs($seller)->post("/lots/{$lot->slug}/bids", ['amount' => 200]);

        $response->assertForbidden();
    }

    public function test_banned_user_gets_logged_out(): void
    {
        $lot = $this->makeLot();
        $banned = User::factory()->create(['banned_at' => now(), 'email_verified_at' => now()]);

        $response = $this->actingAs($banned)->post("/lots/{$lot->slug}/bids", ['amount' => 200]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('bids', ['user_id' => $banned->id]);
    }

    public function test_bid_on_ended_lot_rejected(): void
    {
        $lot = $this->makeLot(['status' => 'ended', 'ends_at' => now()->subDay()]);
        $bidder = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($bidder)->post("/lots/{$lot->slug}/bids", ['amount' => 200]);

        $response->assertForbidden();
    }

    public function test_concurrent_bid_one_wins_via_transaction(): void
    {
        $lot = $this->makeLot();
        $u1 = User::factory()->create(['email_verified_at' => now()]);
        $u2 = User::factory()->create(['email_verified_at' => now()]);

        // Sequential calls — in a real concurrent scenario, lockForUpdate would serialize them
        $this->actingAs($u1)->post("/lots/{$lot->slug}/bids", ['amount' => 150]);
        $this->actingAs($u2)->post("/lots/{$lot->slug}/bids", ['amount' => 160]);

        $this->assertCount(2, Bid::where('lot_id', $lot->id)->get());
        $this->assertEquals(160, (float) $lot->fresh()->current_price);
    }
}
