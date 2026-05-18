<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class LotPolicy
{
    public function update(User $user, Lot $lot): bool
    {
        return $user->id === $lot->seller_id
            && in_array($lot->status, ['draft', 'active'], true)
            && $lot->bids()->doesntExist();
    }

    public function delete(User $user, Lot $lot): bool
    {
        return ($user->id === $lot->seller_id || $user->isAdmin())
            && $lot->bids()->doesntExist();
    }

    public function cancel(User $user, Lot $lot): bool
    {
        return ($user->id === $lot->seller_id || $user->isAdmin())
            && $lot->status === 'active';
    }
}
