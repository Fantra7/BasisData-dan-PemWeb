<?php

namespace App\Filament\Widgets;

use App\Models\JadwalKuliah;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RingkasanJadwalWidget extends BaseWidget
{
    protected static ?string $heading = 'Ringkasan Jadwal Perkuliahan Terkini';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JadwalKuliah::query()->orderByDesc('id')
            )
            ->columns([
                TextColumn::make('kode_kelas')
                    ->label('Kelas'),

                TextColumn::make('mataKuliah.nama_mk')
                    ->label('Mata Kuliah'),

                TextColumn::make('dosen.nama')
                    ->label('Dosen Pengampu'),

                TextColumn::make('hari')
                    ->badge(),

                TextColumn::make('jam_mulai')
                    ->label('Waktu'),

                TextColumn::make('ruang')
                    ->label('Ruangan'),
            ]);
    }
}