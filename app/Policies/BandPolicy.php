<?php

namespace App\Policies;

use App\Models\Band;
use App\Models\User;


class BandPolicy
{   //iedere gebruiker mag een band aanmaken
    public function create(User $user): bool
    {
        return true;
    }

    // wie is beheerder
    public function update(User $user, Band $band): bool
    {
        return $this->isLid($user, $band);
    }

    // permissions geven
    public function manageUsers(User $user, Band $band): bool
    {
        return $this->isLid($user, $band);
    }

    // danger zone
    public function delete(User $user, Band $band): bool
    {
        return $this->isLid($user, $band);
    }

    //check
    private function isLid(User $user, Band $band): bool
    {
        return $band->users()->whereKey($user->id)->exists();
    }
}
