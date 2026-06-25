<?php

namespace App\Policies;

use App\Models\JadwalKuliah;
use App\Models\User;

class JadwalKuliahPolicy
{
    /**
     * Jadwal kuliah disusun oleh admin (bagian akademik).
     * Dosen & mahasiswa hanya boleh melihat jadwal (read-only) -- sesuai
     * keadaan nyata: mereka tidak boleh mengubah jadwal sendiri.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    public function view(User $user, JadwalKuliah $jadwalKuliah): bool
    {
        return $user->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, JadwalKuliah $jadwalKuliah): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, JadwalKuliah $jadwalKuliah): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, JadwalKuliah $jadwalKuliah): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, JadwalKuliah $jadwalKuliah): bool
    {
        return $user->hasRole('admin');
    }
}
