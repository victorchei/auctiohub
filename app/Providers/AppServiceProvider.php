<?php

namespace App\Providers;

use App\Events\AuctionEnded;
use App\Events\BidPlaced;
use App\Listeners\NotifyOutbidUser;
use App\Listeners\SendSellerNotification;
use App\Listeners\SendWinnerNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Event::listen(BidPlaced::class, NotifyOutbidUser::class);
        Event::listen(AuctionEnded::class, SendWinnerNotification::class);
        Event::listen(AuctionEnded::class, SendSellerNotification::class);
    }
}
