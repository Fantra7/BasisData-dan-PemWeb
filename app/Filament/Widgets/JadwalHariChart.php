<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JadwalHariChart extends ChartWidget
{
    // HAPUS KATA 'static' DI BARIS INI:
    protected ?string $heading = 'Data Jadwal Kuliah per Hari';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $dataJadwal = DB::table('jadwal_kuliah')
            ->select('hari', DB::raw('count(*) as jumlah'))
            ->groupBy('hari')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kelas',
                    'data' => $dataJadwal->pluck('jumlah')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $dataJadwal->pluck('hari')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}