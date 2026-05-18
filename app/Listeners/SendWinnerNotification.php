<?php

namespace App\Listeners;

use App\Events\AuctionEnded;
use App\Notifications\AuctionWonNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWinnerNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AuctionEnded $event): void
    {
        if ($event->lot->winner_id && $event->lot->winner) {
            $event->lot->winner->notify(new AuctionWonNotification($event->lot));
        }
    }
}
