<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('latihan_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('paket_latihan_id')->constrained('paket_latihan')->onDelete('cascade');
            $table->integer('percobaan_ke'); // 1, 2, 3, ... 10
            $table->integer('total_soal')->default(0);
            $table->integer('benar')->default(0);
            $table->integer('salah')->default(0);
            $table->integer('kosong')->default(0);
            $table->decimal('nilai', 5, 2)->default(0);
            $table->json('jawaban')->nullable(); // Simpan detail jawaban
            $table->timestamp('mulai_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['siswa_id', 'paket_latihan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('latihan_history');
    }
};