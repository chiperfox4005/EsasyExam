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
        $table->json('log_pelanggaran')->nullable()->after('status');
        $table->integer('jumlah_pelanggaran')->default(0)->after('log_pelanggaran');
    });
}

public function down(): void
{
    Schema::table('hasil_ujian', function (Blueprint $table) {
        $table->dropColumn(['log_pelanggaran', 'jumlah_pelanggaran']);
    });
}
};
