<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coaching;
use App\Models\Journal;
use App\Models\Material;
use App\Models\Reflection;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. USERS
        |--------------------------------------------------------------------------
        */

        $guru = User::updateOrCreate(
            ['email' => 'guru@example.com'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        $murid = User::updateOrCreate(
            ['email' => 'murid@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'murid',
            ]
        );

        $ortu = User::updateOrCreate(
            ['email' => 'ortu@example.com'],
            [
                'name' => 'Pak Andi',
                'password' => Hash::make('password'),
                'role' => 'orang_tua',
            ]
        );

        // Relasi Ortu ↔ Murid
        $ortu->children()->syncWithoutDetaching([$murid->id]);

        /*
        |--------------------------------------------------------------------------
        | 2. COACHING
        |--------------------------------------------------------------------------
        */

        $coaching = Coaching::updateOrCreate(
            [
                'guru_id' => $guru->id,
                'murid_id' => $murid->id,
            ],
            [
                'tujuan' => 'Meningkatkan kepercayaan diri dan fokus belajar',
                'deskripsi' => 'Program coaching 6 bulan',
                'status' => 'completed',
                'final_evaluation' => 'Perkembangan signifikan dalam komunikasi dan disiplin belajar.',
                'completed_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 3. MATERIALS
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 5; $i++) {
            Material::updateOrCreate(
                [
                    'coaching_id' => $coaching->id,
                    'title' => "Materi Sesi $i"
                ],
                [
                    'user_id'     => $guru->id, // 🔥 WAJIB TAMBAH INI
                    'description' => "Materi pengembangan diri sesi ke-$i",
                    'file_path'   => "materials/sesi_$i.pdf"
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. JOURNALS (DATA BESAR UNTUK ANALYTICS)
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 24; $i++) {

            $date = Carbon::now()->subMonths(24 - $i);

            Journal::updateOrCreate(
                [
                    'coaching_id' => $coaching->id,
                    'tanggal' => $date,
                ],
                [
                    'user_id' => $guru->id,
                    'catatan' => "Evaluasi bulan ke-$i menunjukkan peningkatan bertahap.",
                    'refleksi' => "Refleksi bulan ke-$i berjalan cukup baik.",
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. REFLECTION (FINAL)
        |--------------------------------------------------------------------------
        */

        Reflection::create([
            'coaching_id' => $coaching->id,
            'reflection' => 'Murid menunjukkan perkembangan signifikan dalam aspek komunikasi dan kedisiplinan.',
            'hasil_perkembangan' => 'Terjadi peningkatan kepercayaan diri dan partisipasi aktif dalam sesi.',
            'kendala' => 'Masih perlu meningkatkan konsistensi belajar mandiri di rumah.',
            'rencana_perbaikan' => 'Membuat jadwal belajar rutin dan monitoring mingguan bersama guru.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6. FOLLOW UP
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 3; $i++) {
            FollowUp::create([
                'coaching_id' => $coaching->id,
                'judul' => 'Monitoring Mingguan',
                'rencana_tindak_lanjut' => 'Monitoring perkembangan setiap minggu.',
                'target_tanggal' => now()->addWeeks(2),
            ]);
        }
    }
}
