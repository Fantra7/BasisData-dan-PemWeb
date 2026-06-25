<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Database akademik utama berasal dari dump legacy siakad_kelompok3.sql.
        // Jangan membuat user dummy dengan kolom Laravel default seperti `name`,
        // karena tabel legacy bernama `user` hanya memiliki username, email,
        // password, role, dan created_at.
        $this->call(SyncRoleSeeder::class);
    }
}
