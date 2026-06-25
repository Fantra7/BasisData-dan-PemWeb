<?php

namespace App\Observers;

use App\Models\Dosen;
use App\Models\User;

class DosenObserver
{
    /**
     * Ketika data dosen dihapus admin, akun login (user) yang menempel ikut
     * dihapus agar sinkron. Akun admin dilindungi (tidak pernah terhapus).
     */
    public function deleted(Dosen $dosen): void
    {
        if (! $dosen->id_user) {
            return;
        }

        $user = User::find($dosen->id_user);

        if ($user && ! $user->hasRole('admin')) {
            $user->delete();
        }
    }
}
