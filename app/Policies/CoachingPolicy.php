<?php

namespace App\Policies;

use App\Models\Coaching;
use App\Models\User;

class CoachingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Coaching $coaching)
    // {
    //     // guru pemilik
    //     if ($user->role === 'guru' && $coaching->guru_id === $user->id) {
    //         return true;
    //     }

    //     // murid terkait
    //     if ($user->role === 'murid' && $coaching->murid_id === $user->id) {
    //         return true;
    //     }

    //     // orang tua murid
    //     if ($user->role === 'orang_tua' && $coaching->murid->parent_id === $user->id) {
    //         return true;
    //     }

    //     // admin
    //     return $user->role === 'admin';
    // }
    public function view(User $user, Coaching $coaching)
    {
        if ($user->role === 'guru') {
            return $coaching->guru_id === $user->id;
        }

        if ($user->role === 'murid') {
            return $coaching->murid_id === $user->id;
        }

        if ($user->role === 'orang_tua') {
            return $user->children->contains('id', $coaching->murid_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    // public function update(User $user, Coaching $coaching): bool
    // {
    //     return $user->role === 'guru'
    //         && $coaching->guru_id === $user->id;
    // }
    public function update(User $user, Coaching $coaching)
    {
        return $user->role === 'guru'
            && $coaching->guru_id === $user->id;
    }

    // public function delete(User $user, Coaching $coaching): bool
    // {
    //     return $user->role === 'guru'
    //         && $coaching->guru_id === $user->id;
    // }
    public function delete(User $user, Coaching $coaching)
    {
        return $this->update($user, $coaching);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Coaching $coaching): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Coaching $coaching): bool
    {
        return false;
    }
}
