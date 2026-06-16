<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Paket Latihan
        Schema::create('paket_latihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->string('judul'); // misal: "Bahasa Indonesia - Paket 1"
            $table->text('deskripsi')->nullable();
            $table->integer('total_soal')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Pivot: Paket <-> Soal
        Schema::create('paket_latihan_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_latihan_id')->constrained('paket_latihan')->onDelete('cascade');
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->onDelete('cascade');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_latihan_soal');
        Schema::dropIfExists('paket_latihan');
    }
};