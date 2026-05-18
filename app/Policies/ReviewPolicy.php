<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Lot $lot): bool
    {
        return $lot->status === 'ended'
            && $lot->winner_id === $user->id
            && ! $lot->review()->exists();
    }
}
