<?php

namespace App\Policies;

use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TahunAkademikPolicy
{
    /**
     * Determine whether the user can view any models.
     * Mengontrol apakah menu "Tahun Akademik" akan muncul di sidebar Filament.
     */
    public function viewAny(User $user): bool
    {
        // Hanya user dengan role 'admin' dari Spatie yang bisa melihat menu ini
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     * Mengontrol apakah user bisa melihat halaman detail data Tahun Akademik.
     */
    public function view(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     * Mengontrol apakah tombol "New Tahun Akademik" atau form tambah data muncul.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     * Mengontrol hak akses untuk mengedit data Tahun Akademik.
     */
    public function update(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     * Mengontrol tombol hapus (Delete) data Tahun Akademik.
     */
    public function delete(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasRole('admin');
    }
}