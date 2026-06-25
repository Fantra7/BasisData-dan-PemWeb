<?php

namespace App\Filament\Widgets;

use App\Models\Prodi;
use Filament\Widgets\ChartWidget;

class MahasiswaProdiChart extends ChartWidget
{
    // Judul grafik di dashboard
    protected ?string $heading = 'Data Mahasiswa per Program Studi';
    protected static ?int $sort = 1;
    // Tinggi grafik (Wajib ?string berupa teks)
    protected ?string $maxHeight = '300';
    // 1. GANTI INI: Mengimplementasikan fungsi abstrak wajib dari Filament
    public function getType(): string
    {
        return 'bar'; // Bisa diganti 'doughnut' atau 'line' jika mau
    }

    // 2. Mengambil data untuk grafik
    protected function getData(): array
    {
        // Mengambil data prodi beserta hitungan relasi mahasiswanya
        $dataProdi = \DB::table('prodi')
        ->leftJoin('mahasiswa', 'prodi.id', '=', 'mahasiswa.id_prodi')
        ->select('prodi.nama_prodi', \DB::raw('count(mahasiswa.id) as mahasiswa_count'))
        ->groupBy('prodi.id', 'prodi.nama_prodi')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Mahasiswa (Orang)',
                    'data' => $dataProdi->pluck('mahasiswa_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.6)',  // Biru
                        'rgba(255, 99, 132, 0.6)',  // Merah
                        'rgba(255, 206, 86, 0.6)',  // Kuning
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            // Label di bawah grafik diambil dari nama program studi
            'labels' => $dataProdi->pluck('nama_prodi')->toArray(), 
        ];
    }
}