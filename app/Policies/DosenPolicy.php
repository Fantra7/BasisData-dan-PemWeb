<?php

namespace App\Policies;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DosenPolicy
{
    /**
     * Determine whether the user can view any models.
     * Mengontrol apakah menu "Dosen" akan muncul di sidebar Filament.
     */
    public function viewAny(User $user): bool
    {
        // Admin dan Dosen bisa melihat menu Dosen di sidebar. Mahasiswa tidak bisa melihat menu ini.
        return $user->hasAnyRole(['admin', 'dosen']);
    }

    /**
     * Determine whether the user can view the model.
     * Mengontrol apakah user bisa melihat halaman detail data dosen tertentu.
     */
    public function view(User $user, Dosen $dosen): bool
    {
        // Jika yang login adalah Admin, dia bebas melihat detail dosen mana saja
        if ($user->hasRole('admin')) {
            return true;
        }

        // Jika yang login adalah Dosen, dia HANYA bisa melihat detail dirinya sendiri (mencocokkan id_user)
        return $user->hasRole('dosen') && $dosen->id_user === $user->id;
    }

    /**
     * Determine whether the user can create models.
     * Mengontrol siapa yang boleh menambah data dosen baru ke sistem.
     */
    public function create(User $user): bool
    {
        // Hanya Admin yang berhak menambahkan data dosen baru
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     * Mengontrol siapa yang boleh mengedit data dosen.
     */
    public function update(User $user, Dosen $dosen): bool
    {
        // Admin bisa mengedit data dosen siapa saja
        if ($user->hasRole('admin')) {
            return true;
        }

        // Dosen bisa mengedit datanya sendiri (misal untuk memperbarui No HP atau alamat)
        return $user->hasRole('dosen') && $dosen->id_user === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dosen $dosen): bool
    {
        // Hanya Admin yang boleh menghapus data dosen dari sistem
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dosen $dosen): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dosen $dosen): bool
    {
        return $user->hasRole('admin');
    }
}