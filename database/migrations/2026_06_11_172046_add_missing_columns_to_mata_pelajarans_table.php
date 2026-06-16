<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->string('nama')->after('id');
            $table->string('kode')->unique()->after('nama');
            $table->text('deskripsi')->nullable()->after('kode');
            $table->string('icon')->default('fa-book')->after('deskripsi');
            $table->foreignId('kelas_id')->nullable()->after('icon')->constrained('kelas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['nama', 'kode', 'deskripsi', 'icon', 'kelas_id']);
        });
    }
};