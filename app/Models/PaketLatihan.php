<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketLatihan extends Model
{
    protected $table = 'paket_latihan';
    
    protected $fillable = [
        'guru_id',
        'mapel_id',
        'judul',
        'deskripsi',
        'total_soal',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function soal()
    {
        return $this->belongsToMany(BankSoal::class, 'paket_latihan_soal', 'paket_latihan_id', 'bank_soal_id')
                    ->withPivot('urutan')
                    ->orderBy('pivot_urutan', 'asc');
    }
}