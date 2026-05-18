<?php

namespace App\Http\Controllers\Api;

use App\Events\BidPlaced;
use App\Http\Controllers\Controller;
use App\Http\Resources\BidResource;
use App\Models\Bid;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidApiController extends Controller
{
    public function store(Request $request, Lot $lot)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:'.$lot->minNextBid(),
        ]);

        $user = $request->user();
        if ($user->isBanned() || $user->id === $lot->seller_id || $lot->status !== 'active' || $lot->ends_at->isPast()) {
            return response()->json(['message' => 'Forbidden — banned, own lot, or auction not active.'], 403);
        }

        $bid = DB::transaction(function () use ($lot, $user, $data) {
            $fresh = Lot::lockForUpdate()->findOrFail($lot->id);
            if ((float) $data['amount'] < $fresh->minNextBid()) {
                abort(422, 'Lower than current price + increment.');
            }
            $bid = Bid::create([
                'lot_id' => $fresh->id,
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'placed_at' => now(),
            ]);
            $fresh->update(['current_price' => $data['amount']]);
            return $bid;
        });

        BidPlaced::dispatch($bid);

        return new BidResource($bid->load('user'));
    }
}
