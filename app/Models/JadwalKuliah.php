<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliah';
    public $timestamps = false;

    protected $fillable = [
        'kode_kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang',
        'kuota',
        'id_mk',
        'id_dosen',
        'id_ta',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_ta');
    }

    public function detailKrs()
{
    return $this->hasMany(DetailKrs::class, 'id_jadwal');
}
}
