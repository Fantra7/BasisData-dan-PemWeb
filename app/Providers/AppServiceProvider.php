<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Observers\DosenObserver;
use App\Observers\MahasiswaObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Default panjang string untuk kompatibilitas MySQL/MariaDB lama.
        Schema::defaultStringLength(191);

        // Sinkronisasi otomatis: hapus mahasiswa/dosen -> akun login ikut terhapus.
        Mahasiswa::observe(MahasiswaObserver::class);
        Dosen::observe(DosenObserver::class);
    }
}
