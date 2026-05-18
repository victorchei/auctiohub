<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class BidPolicy
{
    public function create(User $user, Lot $lot): bool
    {
        return ! $user->isBanned()
            && $user->id !== $lot->seller_id
            && $lot->status === 'active'
            && $lot->ends_at->isFuture();
    }
}
