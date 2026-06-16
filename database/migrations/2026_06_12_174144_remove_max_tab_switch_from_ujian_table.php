<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            if (Schema::hasColumn('ujian', 'max_tab_switch')) {
                $table->dropColumn('max_tab_switch');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->integer('max_tab_switch')->default(3);
        });
    }
};