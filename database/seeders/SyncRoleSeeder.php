<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SyncRoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole     = Role::firstOrCreate(['name' => 'admin']);
        $dosenRole     = Role::firstOrCreate(['name' => 'dosen']);
        $mahasiswaRole = Role::firstOrCreate(['name' => 'mahasiswa']);

        $map = [
            'admin'     => $adminRole,
            'dosen'     => $dosenRole,
            'mahasiswa' => $mahasiswaRole,
        ];

        foreach (User::all() as $user) {
            // (a) Hash password yang masih plaintext menjadi bcrypt
            if (! str_starts_with((string) $user->password, '$2')) {
                $user->password = Hash::make($user->password ?: '123456');
                $user->save();
            }

            // (b) Tautkan role Spatie sesuai kolom 'role'
            if (isset($map[$user->role]) && ! $user->hasRole($user->role)) {
                $user->assignRole($map[$user->role]);
            }
        }

        $this->command->info('Role tersinkron & password plaintext berhasil di-hash ke bcrypt.');
    }
}
