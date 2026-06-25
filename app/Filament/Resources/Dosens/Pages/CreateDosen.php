<?php

namespace App\Filament\Resources\Dosens\Pages;

use App\Filament\Resources\Dosens\DosenResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateDosen extends CreateRecord
{
    protected static string $resource = DosenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Email institusi dibentuk otomatis dari username
        $email = $data['username'] . '@sttnf.ac.id';

        // 1. Membuat akun user (login) otomatis untuk Dosen
        $user = User::create([
            'username' => $data['username'],
            'email'    => $email,
            'password' => Hash::make('123456'),
            'role'     => 'dosen',
        ]);

        $user->assignRole('dosen');

        // 2. Isi juga kolom email di tabel dosen agar sinkron dengan akun login
        $data['email']   = $email;
        $data['id_user'] = $user->id;
        unset($data['username']);

        return $data;
    }
}
