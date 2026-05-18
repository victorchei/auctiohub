<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    /** @use HasFactory<\Database\Factories\BidFactory> */
    use HasFactory;

    protected $fillable = ['lot_id', 'user_id', 'amount', 'placed_at'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'placed_at' => 'datetime',
        ];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
