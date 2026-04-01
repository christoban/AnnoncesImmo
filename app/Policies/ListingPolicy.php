<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    /**
     * Seul le propriétaire de l'annonce (ou un admin) peut la modifier.
     */
    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id || $user->is_admin;
    }

    /**
     * Seul le propriétaire (ou un admin) peut supprimer.
     */
    public function delete(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id || $user->is_admin;
    }
}
