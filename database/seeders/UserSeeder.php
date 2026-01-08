<?php

namespace Database\Seeders;

use App\Models\User; // ✅ INI YANG KURANG
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sekolah.id'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'guru@sekolah.id'],
            [
                'name' => 'Guru',
                'role' => 'guru',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'murid@sekolah.id'],
            [
                'name' => 'Murid',
                'role' => 'murid',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'ortu@sekolah.id'],
            [
                'name' => 'Orang Tua',
                'role' => 'orang_tua',
                'password' => bcrypt('password'),
            ]
        );
    }
}
