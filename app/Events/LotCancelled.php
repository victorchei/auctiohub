<?php

namespace App\Events;

use App\Models\Lot;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LotCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(public Lot $lot) {}
}
