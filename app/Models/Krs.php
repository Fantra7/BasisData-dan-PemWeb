<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Krs extends Model
{
    protected $table = 'krs';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_krs',
        'status_krs',
        'total_sks',
        'id_mahasiswa',
        'id_ta',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'id_ta');
    }

    public function detailKrs(): HasMany
    {
        return $this->hasMany(DetailKrs::class, 'id_krs');
    }
}