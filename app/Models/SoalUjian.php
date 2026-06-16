<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    protected $table = 'soal_ujian';

    protected $fillable = ['ujian_id', 'bank_soal_id', 'urutan'];

    public function ujian() { return $this->belongsTo(Ujian::class); }
    public function soal() { return $this->belongsTo(BankSoal::class, 'bank_soal_id'); }
}