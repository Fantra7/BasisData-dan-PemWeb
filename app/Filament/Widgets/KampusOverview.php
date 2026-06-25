<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\MataKuliah;

class KampusOverview extends BaseWidget
{
    protected static ?int $sort = 4;
    public static function canView(): bool
    {
        // Statistik umum kampus boleh dilihat semua role yang login.
        return auth()->user()?->hasAnyRole(['admin', 'dosen', 'mahasiswa']) ?? false;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Mahasiswa', Mahasiswa::count() . ' Orang')
                ->description('Mahasiswa aktif terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Total Dosen', Dosen::count() . ' Dosen')
                ->description('Dosen pengajar aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Program Studi', Prodi::count() . ' Prodi')
                ->description('Jurusan yang tersedia')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('warning'),

            Stat::make('Mata Kuliah', MataKuliah::count() . ' MK')
                ->description('Total mata kuliah SIAKAD')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('danger'),
        ];
    }
}
