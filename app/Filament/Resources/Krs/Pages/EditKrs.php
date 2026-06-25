<?php

namespace App\Filament\Resources\Krs\Pages;

use App\Filament\Resources\Krs\KrsResource;
use App\Models\JadwalKuliah;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditKrs extends EditRecord
{
    protected static string $resource = KrsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasRole('admin')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()?->hasRole('mahasiswa') && $this->record->status_krs === 'approved') {
            $this->gagal('KRS yang sudah disetujui tidak boleh diubah oleh mahasiswa.', 'data.status_krs');
        }

        // Repeater pakai ->relationship(), datanya ada di state form ($this->data), bukan di $data
$detailKrs = $this->data['detailKrs'] ?? ($data['detailKrs'] ?? []);
        $this->validateDetailKrs($detailKrs);
        $data['total_sks'] = $this->hitungTotalSks($detailKrs);

        return $data;
    }

    private function gagal(string $pesan, string $field = 'data.detailKrs'): void
    {
        Notification::make()->title('KRS gagal disimpan')->body($pesan)->danger()->send();

        throw ValidationException::withMessages([$field => $pesan]);
    }

    private function hitungTotalSks(array $detailKrs): int
    {
        $ids = collect($detailKrs)->pluck('id_jadwal')->filter()->unique()->values();

        return (int) JadwalKuliah::with('mataKuliah')
            ->whereIn('id', $ids)
            ->get()
            ->sum(fn ($jadwal) => $jadwal->mataKuliah?->sks ?? 0);
    }

    private function validateDetailKrs(array $detailKrs): void
    {
        $ids = collect($detailKrs)->pluck('id_jadwal')->filter()->values();

        if ($ids->isEmpty()) {
            $this->gagal('Minimal pilih satu jadwal kuliah.');
        }

        if ($ids->duplicates()->isNotEmpty()) {
            $this->gagal('Jadwal kuliah tidak boleh dipilih lebih dari satu kali.');
        }

        $jadwals = JadwalKuliah::with('mataKuliah')->whereIn('id', $ids)->get();

        if ($jadwals->pluck('id_mk')->duplicates()->isNotEmpty()) {
            $this->gagal('Mata kuliah yang sama tidak boleh diambil di dua kelas berbeda dalam satu KRS.');
        }

        foreach ($jadwals as $a) {
            foreach ($jadwals as $b) {
                if ($a->id >= $b->id || $a->hari !== $b->hari) {
                    continue;
                }

                if ($a->jam_mulai < $b->jam_selesai && $b->jam_mulai < $a->jam_selesai) {
                    $this->gagal("Jadwal bentrok: {$a->kode_kelas} dan {$b->kode_kelas} pada hari {$a->hari}.");
                }
            }
        }

        if ($this->hitungTotalSks($detailKrs) > 24) {
            $this->gagal('Total SKS tidak boleh lebih dari 24 SKS.');
        }
    }
}
