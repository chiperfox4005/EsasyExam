<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            // Tambah kolom max_attempts (tanpa after, biarkan di akhir)
            if (!Schema::hasColumn('ujian', 'max_attempts')) {
                $table->integer('max_attempts')->default(3);
            }
            
            // Kolom izinkan_upload_gambar_essay sudah ada (lihat dari hasil tinker tadi)
            // Jadi tidak perlu ditambahkan lagi
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn(['max_attempts']);
        });
    }
};