<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';
    public $timestamps = false;

    protected $fillable = [
        'tahun',
        'semester',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function krs()
    {
        return $this->hasMany(Krs::class, 'id_ta');
    }

    public function jadwalKuliah()
{
    return $this->hasMany(JadwalKuliah::class, 'id_ta');
}
}
