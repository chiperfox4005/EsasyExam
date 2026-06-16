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
        if (!Schema::hasColumn('bank_soal', 'penjelasan')) {
            $table->text('penjelasan')->nullable()->after('jawaban_gambar');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            //
        });
    }
};
