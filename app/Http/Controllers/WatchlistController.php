<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index(Request $request)
    {
        $lots = $request->user()
            ->watchlist()
            ->with(['seller', 'category', 'images'])
            ->withCount('bids')
            ->orderBy('lots.ends_at')
            ->paginate(12);

        return view('watchlist.index', compact('lots'));
    }

    public function toggle(Request $request, Lot $lot)
    {
        $user = $request->user();
        $exists = $user->watchlist()->where('lot_id', $lot->id)->exists();

        if ($exists) {
            $user->watchlist()->detach($lot->id);
            $message = 'Видалено зі списку спостереження.';
        } else {
            $user->watchlist()->attach($lot->id);
            $message = 'Додано до списку спостереження.';
        }

        if ($request->wantsJson()) {
            return response()->json(['watching' => ! $exists, 'message' => $message]);
        }

        return back()->with('status', $message);
    }
}
