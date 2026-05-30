<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lot;

class HomeController extends Controller
{
    public function index()
    {
        $featuredLots = Lot::active()
            ->with(['seller', 'category', 'images'])
            ->withCount('bids')
            ->orderByDesc('bids_count')
            ->limit(6)
            ->get();

        $endingSoon = Lot::endingSoon()
            ->with(['seller', 'category', 'images'])
            ->limit(4)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->withCount(['lots as lots_count' => fn ($q) => $q->where('status', 'active')])
            ->with(['children' => fn ($q) => $q->withCount(['lots as lots_count' => fn ($q2) => $q2->where('status', 'active')])])
            ->get()
            ->each(fn ($cat) => $cat->lots_count += $cat->children->sum('lots_count'))
            ->sortByDesc('lots_count')
            ->take(8)
            ->values();

        return view('home', compact('featuredLots', 'endingSoon', 'categories'));
    }
}
