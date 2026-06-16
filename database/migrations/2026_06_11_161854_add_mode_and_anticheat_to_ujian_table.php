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
    Schema::table('ujian', function (Blueprint $table) {
        $table->enum('mode', ['latihan', 'ujian'])->default('ujian')->after('tipe');
        $table->boolean('boleh_copy_paste')->default(false)->after('tampilkan_nilai');
        $table->boolean('deteksi_tab_switch')->default(true)->after('boleh_copy_paste');
        $table->integer('max_tab_switch')->default(3)->after('deteksi_tab_switch');
    });
}

public function down(): void
{
    Schema::table('ujian', function (Blueprint $table) {
        $table->dropColumn(['mode', 'boleh_copy_paste', 'deteksi_tab_switch', 'max_tab_switch']);
    });
}
};
