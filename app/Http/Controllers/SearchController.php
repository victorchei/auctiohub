<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $lots = collect();

        if ($q !== '') {
            $term = '%'.$q.'%';
            $lots = Lot::query()
                ->where('status', 'active')
                ->where(fn ($x) => $x->where('title', 'like', $term)->orWhere('description', 'like', $term))
                ->with(['seller', 'category', 'images'])
                ->withCount('bids')
                ->orderBy('ends_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('search.index', compact('lots', 'q'));
    }
}
