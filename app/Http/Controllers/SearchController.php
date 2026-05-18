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
            $term = '%'.mb_strtolower($q).'%';
            $lots = Lot::query()
                ->where('status', 'active')
                ->where(fn ($x) => $x->whereRaw('LOWER(title) LIKE ?', [$term])->orWhereRaw('LOWER(description) LIKE ?', [$term]))
                ->with(['seller', 'category', 'images'])
                ->withCount('bids')
                ->orderBy('ends_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('search.index', compact('lots', 'q'));
    }
}
