<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $category->load('children', 'parent');
        $childIds = $category->children()->pluck('id');
        $allIds = $childIds->prepend($category->id);

        $lots = $category->lots()
            ->orWhereIn('category_id', $allIds)
            ->where('status', 'active')
            ->with(['seller', 'images'])
            ->withCount('bids')
            ->orderBy('ends_at')
            ->paginate(12);

        return view('categories.show', compact('category', 'lots'));
    }
}
