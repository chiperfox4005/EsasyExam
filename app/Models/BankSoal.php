<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soal';

    protected $fillable = [
        'guru_id',
        'mapel_id',
        'tipe',
        'pertanyaan',
        'level',
        'tampilkan_jawaban',
        'status',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban',
        'penjelasan',
        'opsi_a_tipe',
        'opsi_b_tipe',
        'opsi_c_tipe',
        'opsi_d_tipe',
    ];

    protected $casts = [
        'tampilkan_jawaban' => 'string',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}