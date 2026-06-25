<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\FilamentUser; // Tambah ini untuk integrasi panel Filament
use Filament\Panel; // Tambah ini untuk typing parameter panel
use Spatie\Permission\Traits\HasRoles; // Sudah ter-import dengan benar

class User extends Authenticatable implements HasName, FilamentUser
{
    // Masukkan HasRoles di dalam sini bersama trait lainnya
    use HasFactory, Notifiable, HasRoles; 

    protected $table = 'user'; // Nama tabel di siakad_db

    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role', 
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Membatasi siapa saja yang boleh masuk ke panel Filament menggunakan Role Spatie
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Menggunakan method bawaan Spatie untuk mengecek role
        return $this->hasAnyRole(['admin', 'dosen', 'mahasiswa']);
    }

    /**
     * Trik untuk mematikan fitur Remember Token karena tidak ada di database siakad_db
     */
    public function getRememberToken() { return null; }
    public function setRememberToken($value) {}
    public function getRememberTokenName() { return null; }

    public function getFilamentName(): string
    {
        return $this->username ?? 'User';
    }

    public function dosen(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Dosen::class, 'id_user');
    }

    public function mahasiswa(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'id_user', 'id');
    }
}