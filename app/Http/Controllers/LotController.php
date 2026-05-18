<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lot;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index(Request $request)
    {
        $query = Lot::query()
            ->with(['seller', 'category', 'images'])
            ->withCount('bids');

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $childIds = $category->children()->pluck('id');
                $query->whereIn('category_id', $childIds->prepend($category->id));
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        if ($request->filled('q')) {
            $term = '%'.mb_strtolower($request->q).'%';
            $query->where(fn ($q) => $q->whereRaw('LOWER(title) LIKE ?', [$term])->orWhereRaw('LOWER(description) LIKE ?', [$term]));
        }

        if ($request->filled('min_price')) {
            $query->where('current_price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('current_price', '<=', (float) $request->max_price);
        }

        $sort = $request->get('sort', 'ending_soon');
        match ($sort) {
            'price_asc' => $query->orderBy('current_price'),
            'price_desc' => $query->orderByDesc('current_price'),
            'popular' => $query->orderByDesc('bids_count'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->orderBy('ends_at'),
        };

        $lots = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('lots.index', compact('lots', 'categories', 'sort'));
    }

    public function show(Lot $lot)
    {
        $lot->load([
            'seller',
            'category.parent',
            'images',
            'bids.user',
            'comments.user',
            'comments.replies.user',
            'review.buyer',
            'winner',
        ]);

        $similar = Lot::active()
            ->where('category_id', $lot->category_id)
            ->where('id', '!=', $lot->id)
            ->with(['images', 'seller'])
            ->limit(4)
            ->get();

        $watching = false;
        if (auth()->check()) {
            $watching = auth()->user()->watchlist()->where('lot_id', $lot->id)->exists();
        }

        return view('lots.show', compact('lot', 'similar', 'watching'));
    }
}
