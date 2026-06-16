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
    Schema::create('ujian', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('mapel_id')->constrained('mata_pelajarans')->cascadeOnDelete(); 
        
        $table->string('judul');
        $table->text('deskripsi')->nullable();
        $table->integer('durasi_menit');
        $table->dateTime('mulai_at');
        $table->dateTime('selesai_at');
        $table->enum('tipe', ['latihan', 'uts', 'uas', 'quiz'])->default('quiz');
        $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
        
        $table->boolean('acak_soal')->default(false);
        $table->boolean('acak_opsi')->default(false);
        $table->boolean('tampilkan_nilai')->default(true);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
