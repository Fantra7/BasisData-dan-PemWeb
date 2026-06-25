<?php

namespace App\Filament\Resources\Mahasiswas\Pages;


use App\Filament\Resources\Mahasiswas\MahasiswaResource; 
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateMahasiswa extends CreateRecord
{

    protected static string $resource = MahasiswaResource::class; 

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pembuatan user otomatis
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['username'] . '@sttnf.ac.id', 
            'password' => Hash::make('123456'),
            'role'     => 'mahasiswa',
        ]);

        $user->assignRole('mahasiswa');
        $data['id_user'] = $user->id;
        unset($data['username']);

        return $data;
    }
}