<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jam_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('hari'); // Contoh: Senin, Selasa, Jumat, dll.
            $table->time('jam_masuk');
            $table->time('batas_terlambat'); // jam terakhir sebelum dianggap terlambat
            $table->time('jam_pulang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jam_kerja');
    }
};
