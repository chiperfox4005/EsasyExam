<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soal';

    protected $fillable = [
    'guru_id', 'mapel_id', 'tipe', 'pertanyaan',
    'opsi_a', 'opsi_a_tipe', 'opsi_a_gambar',
    'opsi_b', 'opsi_b_tipe', 'opsi_b_gambar',
    'opsi_c', 'opsi_c_tipe', 'opsi_c_gambar',
    'opsi_d', 'opsi_d_tipe', 'opsi_d_gambar',
    'opsi_e', 'opsi_e_tipe', 'opsi_e_gambar',
    'jawaban', 'jawaban_gambar',
    'gambar_soal', 'level', 'status'
];

    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
    public function mapel() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }
}