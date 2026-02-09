<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CoachingStage;

class CoachingStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoachingStage::insert([
            ['name' => 'Assessment Awal', 'order' => 1],
            ['name' => 'Perencanaan', 'order' => 2],
            ['name' => 'Implementasi', 'order' => 3],
            ['name' => 'Evaluasi', 'order' => 4],
            ['name' => 'Penutupan', 'order' => 5],
        ]);
    }
}
