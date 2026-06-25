<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';
    public $timestamps = false;

    protected $fillable = [
        'id_detail_krs',
        'nilai_angka',
        'nilai_huruf',
        'tanggal_input',
    ];

    protected $casts = [
        'nilai_angka'   => 'decimal:2',
        'tanggal_input' => 'datetime',
    ];

    // Hitung nilai_huruf otomatis saat tambah maupun edit nilai
    protected static function booted(): void
    {
        static::saving(function (Nilai $nilai) {
            if ($nilai->nilai_angka === null) {
                return;
            }

            $nilai->nilai_huruf = static::konversiHuruf((float) $nilai->nilai_angka);

            if (empty($nilai->tanggal_input)) {
                $nilai->tanggal_input = now();
            }
        });
    }

    public static function konversiHuruf(float $angka): string
    {
        return match (true) {
            $angka >= 85 => 'A',
            $angka >= 70 => 'B',
            $angka >= 60 => 'C',
            $angka >= 50 => 'D',
            default      => 'E',
        };
    }

    public function detailKrs()
    {
        return $this->belongsTo(DetailKrs::class, 'id_detail_krs');
    }
}
