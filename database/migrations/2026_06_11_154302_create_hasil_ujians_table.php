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
    Schema::create('hasil_ujian', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ujian_id')->constrained('ujian')->cascadeOnDelete(); 
        $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
        
        $table->json('jawaban')->nullable();
        $table->decimal('nilai', 5, 2)->nullable();
        $table->integer('benar')->default(0);
        $table->integer('salah')->default(0);
        $table->integer('kosong')->default(0);
        
        $table->timestamp('mulai_mengerjakan')->nullable();
        $table->timestamp('submitted_at')->nullable();
        $table->enum('status', ['ongoing', 'submitted', 'graded'])->default('ongoing');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_ujians');
    }
};
