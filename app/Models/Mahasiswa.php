<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'nama',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'angkatan',
        'id_prodi',
        'id_user',
        'foto',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'id_mahasiswa');
    }
}