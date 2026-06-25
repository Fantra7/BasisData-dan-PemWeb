<?php

namespace App\Policies;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MahasiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     * Mengontrol apakah menu "Mahasiswa" akan muncul di sidebar Filament.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Dosen, dan Mahasiswa semuanya harus bisa melihat menu Mahasiswa ini
        return $user->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    /**
     * Determine whether the user can view the model.
     * Mengontrol siapa yang boleh melihat halaman detail dari data seorang mahasiswa.
     */
    public function view(User $user, Mahasiswa $mahasiswa): bool
    {
        // Admin dan Dosen diizinkan melihat detail semua data mahasiswa
        if ($user->hasAnyRole(['admin', 'dosen'])) {
            return true;
        }

        // Mahasiswa HANYA boleh melihat detail data dirinya sendiri (mencocokkan id_user)
        return $user->hasRole('mahasiswa') && $mahasiswa->id_user === $user->id;
    }

    /**
     * Determine whether the user can create models.
     * Mengontrol siapa yang boleh menambah/mendaftarkan mahasiswa baru di sistem.
     */
    public function create(User $user): bool
    {
        // Hanya Admin yang berhak menambahkan akun mahasiswa baru ke database
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     * Mengontrol siapa yang boleh mengubah atau mengedit data profil mahasiswa.
     */
    public function update(User $user, Mahasiswa $mahasiswa): bool
    {
        // Admin bebas mengubah data mahasiswa mana saja
        if ($user->hasRole('admin')) {
            return true;
        }

        // Mahasiswa diizinkan mengedit datanya sendiri (untuk kebutuhan upload foto profil / ganti No HP)
        // Dosen tidak diberikan akses mengedit data pribadi mahasiswa
        return $user->hasRole('mahasiswa') && $mahasiswa->id_user === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Mengontrol tombol hapus data mahasiswa.
     */
    public function delete(User $user, Mahasiswa $mahasiswa): bool
    {
        // Hanya Admin yang boleh menghapus data mahasiswa dari sistem akademik
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mahasiswa $mahasiswa): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mahasiswa $mahasiswa): bool
    {
        return $user->hasRole('admin');
    }
}