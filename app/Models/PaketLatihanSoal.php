<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketLatihanSoal extends Model
{
    protected $table = 'paket_latihan_soal';
    
    protected $fillable = [
        'paket_latihan_id',
        'bank_soal_id',
        'urutan',
    ];
}