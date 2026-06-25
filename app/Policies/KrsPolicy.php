<?php

namespace App\Policies;

use App\Models\Krs;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KrsPolicy
{
    /**
     * Determine whether the user can view any models.
     * Mengontrol apakah menu "KRS" muncul di sidebar Filament.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Dosen, dan Mahasiswa semuanya wajib melihat menu KRS ini
        return $user->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    /**
     * Determine whether the user can view the model.
     * Mengontrol siapa yang boleh melihat detail isi lembar KRS.
     */
    public function view(User $user, Krs $krs): bool
    {
        // Admin dan Dosen bisa melihat detail KRS milik mahasiswa mana saja
        if ($user->hasAnyRole(['admin', 'dosen'])) {
            return true;
        }

        // Mahasiswa HANYA boleh melihat lembar KRS miliknya sendiri
        return $user->hasRole('mahasiswa') && $krs->mahasiswa?->id_user === $user->id;
    }

    /**
     * Determine whether the user can create models.
     * Mengontrol siapa yang boleh membuat pengajuan KRS baru.
     */
    public function create(User $user): bool
    {
        // Admin bisa membuatkan KRS, dan Mahasiswa berhak melakukan pengisian KRS secara mandiri
        // Dosen tidak mengisi KRS, melainkan hanya menyetujui (update status)
        return $user->hasAnyRole(['admin', 'mahasiswa']);
    }

    /**
     * Determine whether the user can update the model.
     * Mengontrol hak akses pengubahan data KRS.
     */
    public function update(User $user, Krs $krs): bool
    {
        // Admin bebas mengubah data KRS apa saja
        // Dosen diizinkan melakukan update (berguna saat menekan tombol approve/mengubah status_krs)
        if ($user->hasAnyRole(['admin', 'dosen'])) {
            return true;
        }

        // Mahasiswa hanya boleh mengubah KRS miliknya sendiri sebelum divalidasi
        return $user->hasRole('mahasiswa') && $krs->mahasiswa?->id_user === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Mengontrol tombol hapus lembar KRS.
     */
    public function delete(User $user, Krs $krs): bool
    {
        // Demi integritas data akademik agar nilai/pilihan kelas tidak hilang sembarangan,
        // hanya Admin yang memiliki hak mutlak untuk menghapus data KRS dari sistem.
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Krs $krs): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Krs $krs): bool
    {
        return $user->hasRole('admin');
    }
}