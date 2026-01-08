<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;

class JournalPolicy
{
    /**
     * Lihat jurnal
     */
    public function view(User $user, Journal $journal): bool
    {
        $coaching = $journal->coaching;

        // ADMIN
        if ($user->role === 'admin') {
            return true;
        }

        // GURU PEMILIK
        if ($user->role === 'guru' && $coaching->guru_id === $user->id) {
            return true;
        }

        // MURID PEMILIK
        if ($user->role === 'murid' && $coaching->murid_id === $user->id) {
            return true;
        }

        // ORANG TUA MURID
        if (
            $user->role === 'orang_tua'
            && $coaching->murid->parent_id === $user->id
        ) {
            return true;
        }

        return false;
    }

    /**
     * Guru boleh membuat jurnal
     */
    public function create(User $user): bool
    {
        return $user->role === 'guru';
    }

    /**
     * Update jurnal (hanya guru pemilik)
     */
    public function update(User $user, Journal $journal): bool
    {
        return $user->role === 'guru'
            && $journal->coaching->guru_id === $user->id;
    }

    /**
     * Hapus jurnal (opsional)
     */
    public function delete(User $user, Journal $journal): bool
    {
        return $this->update($user, $journal);
    }
}
