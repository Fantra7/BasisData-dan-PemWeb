<?php

namespace App\Filament\Resources\Krs\Pages;

use App\Filament\Resources\Krs\KrsResource;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateKrs extends CreateRecord
{
    protected static string $resource = KrsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        // Mahasiswa: identitas & status diisi otomatis oleh sistem (field-nya disabled di form)
        if ($user?->hasRole('mahasiswa')) {
            $data['id_mahasiswa'] = Mahasiswa::where('id_user', $user->id)->value('id');
            $data['status_krs'] = 'submitted';
        }

        if (empty($data['id_mahasiswa'])) {
            $this->gagal('Mahasiswa tidak dikenali. Pastikan akun ini tertaut ke data mahasiswa.', 'data.id_mahasiswa');
        }

        if (empty($data['status_krs'])) {
            $data['status_krs'] = 'submitted';
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

        if ($ids->isEmpty()) {
    $this->gagal('Minimal pilih satu jadwal kuliah.');
}
    }
}
