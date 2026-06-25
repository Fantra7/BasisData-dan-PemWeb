<?php

namespace App\Policies;

use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MataKuliahPolicy
{
    /**
     * Determine whether the user can view any models.
     * Mengontrol apakah menu "Mata Kuliah" akan muncul di sidebar Filament.
     */
    public function viewAny(User $user): bool
    {
        // Semua role boleh melihat daftar mata kuliah (read-only untuk dosen & mahasiswa).
        return $user->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    /**
     * Determine whether the user can view the model.
     * Mengontrol apakah user bisa melihat halaman detail data Mata Kuliah.
     */
    public function view(User $user, MataKuliah $mataKuliah): bool
    {
        return $user->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    /**
     * Determine whether the user can create models.
     * Mengontrol apakah tombol "New Mata Kuliah" atau form tambah data muncul.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     * Mengontrol hak akses untuk mengedit data Mata Kuliah.
     */
    public function update(User $user, MataKuliah $mataKuliah): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     * Mengontrol tombol hapus (Delete) data Mata Kuliah.
     */
    public function delete(User $user, MataKuliah $mataKuliah): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MataKuliah $mataKuliah): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MataKuliah $mataKuliah): bool
    {
        return $user->hasRole('admin');
    }
}