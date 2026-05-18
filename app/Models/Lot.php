<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    /** @use HasFactory<\Database\Factories\LotFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'category_id',
        'winner_id',
        'title',
        'slug',
        'description',
        'starting_price',
        'current_price',
        'bid_increment',
        'starts_at',
        'ends_at',
        'status',
        'cover_image_path',
    ];

    protected function casts(): array
    {
        return [
            'starting_price' => 'decimal:2',
            'current_price' => 'decimal:2',
            'bid_increment' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(LotImage::class)->orderBy('sort_order');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class)->orderByDesc('placed_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('replies');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watchlist')->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeEndingSoon(Builder $query): Builder
    {
        return $query->active()->orderBy('ends_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    public function minNextBid(): float
    {
        return (float) $this->current_price + (float) $this->bid_increment;
    }
}
