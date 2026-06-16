<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajarans';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'icon',
        'kelas_id',
        'guru_id'
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }

    public function guru() {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function bankSoal() {
        return $this->hasMany(BankSoal::class, 'mapel_id');
    }

    public function ujian() {
        return $this->hasMany(Ujian::class, 'mapel_id');
    }
}