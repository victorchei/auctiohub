<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'starting_price' => (float) $this->starting_price,
            'current_price' => (float) $this->current_price,
            'bid_increment' => (float) $this->bid_increment,
            'min_next_bid' => $this->minNextBid(),
            'starts_at' => $this->starts_at?->toIso8601String(),
            'ends_at' => $this->ends_at?->toIso8601String(),
            'status' => $this->status,
            'seller' => $this->whenLoaded('seller', fn () => ['id' => $this->seller->id, 'name' => $this->seller->name]),
            'category' => $this->whenLoaded('category', fn () => ['id' => $this->category->id, 'name' => $this->category->name, 'slug' => $this->category->slug]),
            'winner_id' => $this->winner_id,
            'images_count' => $this->whenCounted('images', $this->images_count ?? 0),
            'bids_count' => $this->whenCounted('bids', $this->bids_count ?? 0),
        ];
    }
}
