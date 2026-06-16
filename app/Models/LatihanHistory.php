<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatihanHistory extends Model
{
    protected $table = 'latihan_history';
    
    protected $fillable = [
        'siswa_id',
        'paket_latihan_id',
        'percobaan_ke',
        'total_soal',
        'benar',
        'salah',
        'kosong',
        'nilai',
        'jawaban',
        'mulai_at',
        'selesai_at',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'nilai' => 'decimal:2',
        'mulai_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function paket()
    {
        return $this->belongsTo(PaketLatihan::class, 'paket_latihan_id');
    }
}