<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;

class NilaiPolicy
{
    // Siapa saja yang bisa melihat daftar halaman Nilai
    public function viewAny(User $user): bool
    {
        return true; // Semua role (Admin, Dosen, Mahasiswa) bisa buka menu Nilai
    }

    // Hanya Admin dan Dosen yang bisa membuat Nilai baru
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('dosen');
        // Otomatis mahasiswa akan mengembalikan nilai false, dan tombol "New" akan HILANG
    }

    // Hanya Admin dan Dosen yang bisa mengedit Nilai
    public function update(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('admin') || $user->hasRole('dosen');
        // Otomatis tombol "Edit" HILANG di akun mahasiswa
    }

    // Hanya Admin yang bisa menghapus data nilai
    public function delete(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('admin');
    }
}