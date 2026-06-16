<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    protected $table = 'hasil_ujian';

    protected $fillable = [
    'ujian_id', 'siswa_id', 'nilai', 'benar', 'salah', 'kosong',
    'mulai_mengerjakan', 'submitted_at', 'status', 'jawaban',
    'pelanggaran', 'tab_switch_count', 'copy_count', 'paste_count',
    'right_click_count', 'blur_count'
];

    protected $casts = [
        'jawaban' => 'array', 
        'log_pelanggaran' => 'array',
        'mulai_mengerjakan' => 'datetime', 
        'submitted_at' => 'datetime'
    ];

    public function ujian() { return $this->belongsTo(Ujian::class); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
}