<?php

namespace App\Policies;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProdiPolicy
{
    /**
     * Determine whether the user can view any models.
     * Mengontrol apakah menu "Prodi" akan muncul di sidebar Filament.
     */
    public function viewAny(User $user): bool
    {
        // Hanya user dengan role 'admin' dari Spatie yang bisa melihat menu ini
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     * Mengontrol apakah user bisa membuka halaman detail data Prodi.
     */
    public function view(User $user, Prodi $prodi): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     * Mengontrol apakah tombol "New Prodi" atau form tambah data muncul.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     * Mengontrol hak akses untuk mengedit data Prodi.
     */
    public function update(User $user, Prodi $prodi): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     * Mengontrol tombol hapus (Delete) data Prodi.
     */
    public function delete(User $user, Prodi $prodi): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Prodi $prodi): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Prodi $prodi): bool
    {
        return $user->hasRole('admin');
    }
}