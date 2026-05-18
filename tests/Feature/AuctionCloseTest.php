<?php

namespace Tests\Feature;

use App\Events\AuctionEnded;
use App\Models\Bid;
use App\Models\Category;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuctionCloseTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_closes_expired_lot_and_sets_winner(): void
    {
        Event::fake();

        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $category = Category::factory()->create();

        $lot = Lot::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'Expired lot',
            'slug' => 'expired-lot-'.uniqid(),
            'description' => 'desc',
            'starting_price' => 100,
            'current_price' => 100,
            'bid_increment' => 10,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subMinute(),
            'status' => 'active',
        ]);

        Bid::create([
            'lot_id' => $lot->id,
            'user_id' => $bidder->id,
            'amount' => 200,
            'placed_at' => now()->subHour(),
        ]);

        $this->artisan('auctions:close')->assertSuccessful();

        $lot->refresh();
        $this->assertEquals('ended', $lot->status);
        $this->assertEquals($bidder->id, $lot->winner_id);

        Event::assertDispatched(AuctionEnded::class, fn ($event) => $event->lot->id === $lot->id);
    }

    public function test_command_does_not_touch_active_lots_with_future_end(): void
    {
        $seller = User::factory()->create();
        $category = Category::factory()->create();

        $lot = Lot::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'Active lot',
            'slug' => 'active-lot-'.uniqid(),
            'description' => 'desc',
            'starting_price' => 100,
            'current_price' => 100,
            'bid_increment' => 10,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDay(),
            'status' => 'active',
        ]);

        $this->artisan('auctions:close')->assertSuccessful();

        $this->assertEquals('active', $lot->fresh()->status);
    }

    public function test_expired_lot_with_no_bids_has_null_winner(): void
    {
        $seller = User::factory()->create();
        $category = Category::factory()->create();

        $lot = Lot::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'No bids',
            'slug' => 'nobids-'.uniqid(),
            'description' => 'desc',
            'starting_price' => 100,
            'current_price' => 100,
            'bid_increment' => 10,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->subMinute(),
            'status' => 'active',
        ]);

        $this->artisan('auctions:close')->assertSuccessful();

        $lot->refresh();
        $this->assertEquals('ended', $lot->status);
        $this->assertNull($lot->winner_id);
    }
}
