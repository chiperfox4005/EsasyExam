<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            // Tipe konten per opsi: text, image, hybrid
            $table->string('opsi_a_tipe')->default('text')->after('opsi_a');
            $table->string('opsi_a_gambar')->nullable()->after('opsi_a_tipe');
            
            $table->string('opsi_b_tipe')->default('text')->after('opsi_b');
            $table->string('opsi_b_gambar')->nullable()->after('opsi_b_tipe');
            
            $table->string('opsi_c_tipe')->default('text')->after('opsi_c');
            $table->string('opsi_c_gambar')->nullable()->after('opsi_c_tipe');
            
            $table->string('opsi_d_tipe')->default('text')->after('opsi_d');
            $table->string('opsi_d_gambar')->nullable()->after('opsi_d_tipe');
            
            $table->string('opsi_e_tipe')->default('text')->after('opsi_e');
            $table->string('opsi_e_gambar')->nullable()->after('opsi_e_tipe');
            
            // Gambar untuk jawaban essay
            $table->string('jawaban_gambar')->nullable()->after('jawaban');
        });
    }

    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropColumn([
                'opsi_a_tipe', 'opsi_a_gambar',
                'opsi_b_tipe', 'opsi_b_gambar',
                'opsi_c_tipe', 'opsi_c_gambar',
                'opsi_d_tipe', 'opsi_d_gambar',
                'opsi_e_tipe', 'opsi_e_gambar',
                'jawaban_gambar',
            ]);
        });
    }
};