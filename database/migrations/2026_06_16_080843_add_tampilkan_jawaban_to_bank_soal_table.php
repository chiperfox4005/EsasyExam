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
    Schema::table('bank_soal', function (Blueprint $table) {
        if (!Schema::hasColumn('bank_soal', 'tampilkan_jawaban')) {
            $table->string('tampilkan_jawaban')->default('immediate')->after('level');
            // 'immediate' = langsung setelah submit
            // 'after_submit' = setelah ujian selesai
        }
        
        if (!Schema::hasColumn('bank_soal', 'penjelasan')) {
            $table->text('penjelasan')->nullable()->after('jawaban');
        }
    });
}

public function down(): void
{
    Schema::table('bank_soal', function (Blueprint $table) {
        $table->dropColumn(['tampilkan_jawaban', 'penjelasan']);
    });
}
};
