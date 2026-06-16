<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';

    protected $fillable = [
        'guru_id', 'mapel_id', 'kelas_id', 'judul', 'deskripsi',
        'durasi_menit', 'mulai_at', 'selesai_at', 'tipe', 'mode',
        'status', 'acak_soal', 'acak_opsi', 'tampilkan_nilai',
        'boleh_copy_paste', 'deteksi_tab_switch', 'max_tab_switch',
        'max_attempts', 'izinkan_upload_gambar_essay'
    ];

    protected $casts = [
        'mulai_at' => 'datetime', 
        'selesai_at' => 'datetime',
        'acak_soal' => 'boolean', 
        'acak_opsi' => 'boolean', 
        'tampilkan_nilai' => 'boolean',
        'boleh_copy_paste' => 'boolean',
        'deteksi_tab_switch' => 'boolean'
    ];

    // ========== RELASI YANG SUDAH ADA (TIDAK DIUBAH) ==========
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
    public function mapel() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function soalUjian() { return $this->hasMany(SoalUjian::class); }
    public function hasilUjian() { return $this->hasMany(HasilUjian::class); }

    // ========== RELASI BARU (TAMBAHAN) ==========
    // Relasi langsung ke soal-soal melalui tabel pivot soal_ujian
    public function soalSoal()
    {
        return $this->hasManyThrough(
            BankSoal::class,
            SoalUjian::class,
            'ujian_id',        // Foreign key di soal_ujian
            'id',              // Primary key di bank_soal
            'id',              // Primary key di ujian
            'bank_soal_id'     // Foreign key di soal_ujian
        );
    }
}