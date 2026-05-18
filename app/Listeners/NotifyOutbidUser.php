<?php

namespace App\Listeners;

use App\Events\BidPlaced;
use App\Models\Bid;
use App\Notifications\OutbidNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyOutbidUser implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BidPlaced $event): void
    {
        $bid = $event->bid;

        $previousHighest = Bid::where('lot_id', $bid->lot_id)
            ->where('id', '!=', $bid->id)
            ->where('user_id', '!=', $bid->user_id)
            ->orderByDesc('amount')
            ->first();

        if ($previousHighest) {
            $previousHighest->user->notify(new OutbidNotification($bid->lot, (float) $bid->amount));
        }
    }
}
