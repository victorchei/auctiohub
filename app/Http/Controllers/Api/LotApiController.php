<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BidResource;
use App\Http\Resources\LotResource;
use App\Models\Lot;
use Illuminate\Http\Request;

class LotApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lot::query()->with(['seller', 'category'])->withCount(['bids', 'images']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $query->orderBy('ends_at');

        return LotResource::collection($query->paginate(20));
    }

    public function show(Lot $lot)
    {
        $lot->load(['seller', 'category', 'images'])->loadCount('bids');

        return new LotResource($lot);
    }

    public function bids(Lot $lot)
    {
        $bids = $lot->bids()->with('user')->orderByDesc('placed_at')->paginate(50);

        return BidResource::collection($bids);
    }
}
