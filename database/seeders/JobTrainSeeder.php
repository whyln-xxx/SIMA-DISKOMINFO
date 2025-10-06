<?php

namespace Database\Seeders;

use App\Models\JobTrain;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTrainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobTrain::create([
            'kode' => 'J001',
            'nama' => 'Mahasiswa',
        ]);
        JobTrain::create([
            'kode' => 'J002',
            'nama' => 'Siswa',
        ]);
    }
}
