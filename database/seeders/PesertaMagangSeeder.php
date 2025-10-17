<?php

namespace Database\Seeders;

use App\Models\PesertaMagang;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PesertaMagang::create([
            'npm' => '12345',
            'jobtrain_id' => '1',
            'nama_lengkap' => 'Ucup',
            'foto' => '12345.jpg',
            'pendidikan' => 'SMK',
            'telepon' => '08123456789',
            'email' => 'ucup@gmail.com',
            'password' => Hash::make('password'),
        ]);

        PesertaMagang::create([
            'npm' => '12346',
            'jobtrain_id' => '2',
            'nama_lengkap' => 'Wati',
            'pendidikan' => 'Universitas',
            'telepon' => '08123456780',
            'email' => 'wati@gmail.com',
            'password' => Hash::make('password'),
        ]);;
    }
}
