<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('lots')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
        ]);

        $data['slug'] = Str::slug($data['name']).'-'.uniqid();
        Category::create($data);

        return back()->with('status', 'Категорію створено.');
    }

    public function destroy(Category $category)
    {
        if ($category->lots()->exists() || $category->children()->exists()) {
            return back()->with('error', 'Не можна видалити: є лоти або підкатегорії.');
        }
        $category->delete();

        return back()->with('status', 'Категорію видалено.');
    }
}
