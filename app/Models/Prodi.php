<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    public $timestamps = false; // Karena di SQL tidak ada created_at/updated_at

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_prodi');
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'id_prodi');
    }

    public function dosen()
{
    return $this->hasMany(Dosen::class, 'id_prodi');
}
}
