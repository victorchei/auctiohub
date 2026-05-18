<?php

namespace App\Http\Controllers;

use App\Events\BidPlaced;
use App\Http\Requests\PlaceBidRequest;
use App\Models\Bid;
use App\Models\Lot;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(PlaceBidRequest $request, Lot $lot)
    {
        $amount = (float) $request->input('amount');

        $bid = DB::transaction(function () use ($lot, $request, $amount) {
            $fresh = Lot::lockForUpdate()->findOrFail($lot->id);

            if ($amount < $fresh->minNextBid()) {
                abort(422, 'Ставка нижча за поточну + крок. Спробуйте ще раз.');
            }
            if ($fresh->status !== 'active' || $fresh->ends_at->isPast()) {
                abort(422, 'Аукціон завершено або неактивний.');
            }

            $bid = Bid::create([
                'lot_id' => $fresh->id,
                'user_id' => $request->user()->id,
                'amount' => $amount,
                'placed_at' => now(),
            ]);

            $fresh->update(['current_price' => $amount]);

            return $bid;
        });

        BidPlaced::dispatch($bid);

        return redirect()->route('lots.show', $lot)->with('status', 'Ставку зроблено!');
    }
}
