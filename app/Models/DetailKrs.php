<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DetailKrs extends Model
{
    protected $table = 'detail_krs';
    public $timestamps = false;

    protected $fillable = [
        'id_krs',
        'id_jadwal',
    ];

    public function krs()
    {
        return $this->belongsTo(Krs::class, 'id_krs');
    }

    public function jadwalKuliah(): BelongsTo
{
    return $this->belongsTo(JadwalKuliah::class, 'id_jadwal');
}

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'id_detail_krs');
    }
}