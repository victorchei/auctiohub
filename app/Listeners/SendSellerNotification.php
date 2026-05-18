<?php

namespace App\Listeners;

use App\Events\AuctionEnded;
use App\Notifications\AuctionEndedSellerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSellerNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AuctionEnded $event): void
    {
        $event->lot->seller->notify(new AuctionEndedSellerNotification($event->lot));
    }
}
