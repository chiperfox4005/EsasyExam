<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hasil_ujian', function (Blueprint $table) {
            // Tambah kolom started_at jika belum ada
            if (!Schema::hasColumn('hasil_ujian', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('jawaban');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_ujian', function (Blueprint $table) {
            if (Schema::hasColumn('hasil_ujian', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};