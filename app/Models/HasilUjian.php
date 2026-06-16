<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujian';

    protected $fillable = [
    'ujian_id',
    'siswa_id',
    'status',
    'jawaban',
    'nilai',
    'benar',        // ✅ TAMBAHKAN
    'salah',        // ✅ TAMBAHKAN
    'kosong',       // ✅ TAMBAHKAN
    'tab_switch_count',   // ✅ TAMBAHKAN
    'copy_count',         // ✅ TAMBAHKAN
    'paste_count',        // ✅ TAMBAHKAN
    'right_click_count',  // ✅ TAMBAHKAN
    'blur_count',         // ✅ TAMBAHKAN
    'mulai_mengerjakan',  // ✅ TAMBAHKAN
    'submitted_at',       // ✅ TAMBAHKAN
];

    protected $casts = [
        'jawaban' => 'array',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}