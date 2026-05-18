<?php

namespace App\Console\Commands;

use App\Events\AuctionEnded;
use App\Models\Bid;
use App\Models\Lot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close';
    protected $description = 'Закриває активні аукціони, у яких ends_at вже минув. Встановлює winner_id і кидає AuctionEnded event.';

    public function handle(): int
    {
        $expired = Lot::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->get();

        if ($expired->isEmpty()) {
            $this->info('Жодного expired лоту.');
            return self::SUCCESS;
        }

        $closed = 0;

        foreach ($expired as $lot) {
            DB::transaction(function () use ($lot, &$closed) {
                $highest = Bid::where('lot_id', $lot->id)->orderByDesc('amount')->first();
                $lot->update([
                    'status' => 'ended',
                    'winner_id' => $highest?->user_id,
                ]);
                $lot->refresh();
                AuctionEnded::dispatch($lot);
                $closed++;
            });
            $this->info("Закрито лот #{$lot->id}: {$lot->title}");
        }

        $this->info("Усього закрито: {$closed}");
        return self::SUCCESS;
    }
}
