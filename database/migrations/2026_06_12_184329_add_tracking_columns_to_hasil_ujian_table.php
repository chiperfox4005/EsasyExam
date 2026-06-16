<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_ujian', function (Blueprint $table) {
            if (!Schema::hasColumn('hasil_ujian', 'tab_switch_count')) {
                $table->integer('tab_switch_count')->default(0);
            }
            if (!Schema::hasColumn('hasil_ujian', 'copy_count')) {
                $table->integer('copy_count')->default(0);
            }
            if (!Schema::hasColumn('hasil_ujian', 'paste_count')) {
                $table->integer('paste_count')->default(0);
            }
            if (!Schema::hasColumn('hasil_ujian', 'right_click_count')) {
                $table->integer('right_click_count')->default(0);
            }
            if (!Schema::hasColumn('hasil_ujian', 'blur_count')) {
                $table->integer('blur_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hasil_ujian', function (Blueprint $table) {
            $table->dropColumn([
                'tab_switch_count',
                'copy_count',
                'paste_count',
                'right_click_count',
                'blur_count'
            ]);
        });
    }
};