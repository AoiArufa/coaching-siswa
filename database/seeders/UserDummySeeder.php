<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserDummySeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // GURU
        // ========================

        User::updateOrCreate(
            ['email' => 'guru1@example.com'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        User::updateOrCreate(
            ['email' => 'guru2@example.com'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        // ========================
        // MURID
        // ========================

        User::updateOrCreate(
            ['email' => 'murid1@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'murid',
            ]
        );

        User::updateOrCreate(
            ['email' => 'murid2@example.com'],
            [
                'name' => 'Rina Putri',
                'password' => Hash::make('password'),
                'role' => 'murid',
            ]
        );

        // ========================
        // ORANG TUA
        // ========================

        User::updateOrCreate(
            ['email' => 'ortu1@example.com'],
            [
                'name' => 'Pak Andi',
                'password' => Hash::make('password'),
                'role' => 'orang_tua',
            ]
        );

        User::updateOrCreate(
            ['email' => 'ortu2@example.com'],
            [
                'name' => 'Bu Lina',
                'password' => Hash::make('password'),
                'role' => 'orang_tua',
            ]
        );
    }
}
