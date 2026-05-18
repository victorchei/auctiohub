<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLotRequest;
use App\Models\Category;
use App\Models\Lot;
use App\Models\LotImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LotManageController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('lots.create', compact('categories'));
    }

    public function store(StoreLotRequest $request)
    {
        $data = $request->validated();

        $lot = Lot::create([
            'seller_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.time(),
            'description' => $data['description'],
            'starting_price' => $data['starting_price'],
            'current_price' => $data['starting_price'],
            'bid_increment' => $data['bid_increment'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'],
            'status' => $data['starts_at'] <= now()->format('Y-m-d H:i:s') ? 'active' : 'draft',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $image) {
                $path = $image->store('lots', 'public');
                LotImage::create([
                    'lot_id' => $lot->id,
                    'path' => $path,
                    'sort_order' => $idx,
                ]);
            }
        }

        return redirect()->route('lots.show', $lot)->with('status', 'Лот створено.');
    }

    public function edit(Lot $lot)
    {
        $this->authorize('update', $lot);
        $categories = Category::orderBy('name')->get();

        return view('lots.edit', compact('lot', 'categories'));
    }

    public function update(StoreLotRequest $request, Lot $lot)
    {
        $this->authorize('update', $lot);
        $lot->update($request->validated());

        return redirect()->route('lots.show', $lot)->with('status', 'Лот оновлено.');
    }

    public function destroy(Lot $lot)
    {
        $this->authorize('delete', $lot);
        $lot->delete();

        return redirect()->route('home')->with('status', 'Лот видалено.');
    }
}
