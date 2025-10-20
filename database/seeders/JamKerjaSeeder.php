<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JamKerjaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jam_kerja')->insert([
            [
                'hari' => 'Senin-Kamis',
                'jam_masuk' => '05:30:00',
                'batas_terlambat' => '09:00:00',
                'jam_keluar' => '16:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hari' => 'Jumat',
                'jam_masuk' => '05:30:00',
                'batas_terlambat' => '09:00:00',
                'jam_keluar' => '16:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
    }
}
