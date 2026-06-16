<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\HasilUjian;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    /**
     * List ujian yang tersedia untuk siswa
     */
    public function index()
    {
        $siswaId = Auth::id();
        $siswa = Auth::user();
        $now = now();
        
        // Ambil ujian yang tersedia
        $ujianList = Ujian::with(['mapel', 'soalUjian'])
            ->where('status', 'published')
            ->where('mulai_at', '<=', $now)
            ->where('selesai_at', '>=', $now)
            ->where(function($query) use ($siswa) {
                $query->whereNull('kelas_id')
                      ->orWhere('kelas_id', $siswa->kelas_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($ujian) use ($siswaId) {
                // Cek apakah siswa sudah mengerjakan
                $hasil = HasilUjian::where('ujian_id', $ujian->id)
                    ->where('siswa_id', $siswaId)
                    ->first();
                
                $ujian->sudah_dikerjakan = $hasil !== null;
                $ujian->nilai = $hasil ? $hasil->nilai : null;
                $ujian->rata_rata_nilai = $hasil ? $hasil->nilai : null;
                $ujian->status_pengerjaan = $hasil ? $hasil->status : 'belum';
                
                return $ujian;
            });
        
        return view('siswa.ujian.index', compact('ujianList'));
    }
    
    /**
     * Riwayat ujian siswa
     */
    public function riwayat()
    {
        $siswaId = Auth::id();
        
        $riwayatList = HasilUjian::with(['ujian.mapel'])
            ->where('siswa_id', $siswaId)
            ->where('status', 'completed')
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);
        
        return view('siswa.ujian.riwayat', compact('riwayatList'));
    }
}