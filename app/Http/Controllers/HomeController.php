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
            ->withCount('lots')
            ->orderByDesc('lots_count')
            ->limit(8)
            ->get();

        return view('home', compact('featuredLots', 'endingSoon', 'categories'));
    }
}
