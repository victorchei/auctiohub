<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LotResource;
use App\Models\Lot;
use Illuminate\Http\Request;

class WatchlistApiController extends Controller
{
    public function index(Request $request)
    {
        $lots = $request->user()->watchlist()->with('category', 'seller')->withCount('bids')->paginate(20);

        return LotResource::collection($lots);
    }

    public function toggle(Request $request, Lot $lot)
    {
        $user = $request->user();
        $exists = $user->watchlist()->where('lot_id', $lot->id)->exists();

        if ($exists) {
            $user->watchlist()->detach($lot->id);
        } else {
            $user->watchlist()->attach($lot->id);
        }

        return response()->json(['watching' => ! $exists]);
    }
}
