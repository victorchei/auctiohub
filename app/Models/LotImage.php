<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LotImage extends Model
{
    /** @use HasFactory<\Database\Factories\LotImageFactory> */
    use HasFactory;

    protected $fillable = ['lot_id', 'path', 'sort_order'];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}
