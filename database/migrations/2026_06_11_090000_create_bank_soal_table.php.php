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
    Schema::create('bank_soal', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('mapel_id')->constrained('mata_pelajarans')->cascadeOnDelete(); 
        
        $table->enum('tipe', ['pg', 'essay', 'gambar'])->default('pg');
        $table->text('pertanyaan');
        
        $table->text('opsi_a')->nullable();
        $table->text('opsi_b')->nullable();
        $table->text('opsi_c')->nullable();
        $table->text('opsi_d')->nullable();
        $table->text('opsi_e')->nullable();
        
        $table->string('jawaban')->nullable(); 
        $table->string('gambar_soal')->nullable(); 
        
        $table->enum('level', ['mudah', 'sedang', 'sulit'])->default('sedang');
        $table->enum('status', ['draft', 'published'])->default('draft');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal');
    }
};
