<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'tingkat', 'tipe', 'wali_kelas_id'];

    public function waliKelas() {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function mataPelajaran() {
        return $this->hasMany(MataPelajaran::class);
    }
}