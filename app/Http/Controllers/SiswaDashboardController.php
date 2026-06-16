<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::user();
        $now = now();
        
        // 1. Ujian yang sedang berlangsung (pakai Eloquent + eager load)
        $ujianAktif = Ujian::with(['mapel', 'soalUjian'])
            ->where('status', 'published')
            ->where('mulai_at', '<=', $now)
            ->where('selesai_at', '>=', $now)
            ->where(function($q) use ($siswa) {
                $q->whereNull('kelas_id')
                  ->orWhere('kelas_id', $siswa->kelas_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // 2. Total ujian aktif
        $totalUjianAktif = Ujian::where('status', 'published')
            ->where('mulai_at', '<=', $now)
            ->where('selesai_at', '>=', $now)
            ->where(function($q) use ($siswa) {
                $q->whereNull('kelas_id')
                  ->orWhere('kelas_id', $siswa->kelas_id);
            })
            ->count();
        
        // 3. Ujian yang sudah dikerjakan
        $ujianDikerjakan = HasilUjian::where('siswa_id', $siswa->id)
            ->where('status', 'graded')
            ->count();
        
        // 4. Rata-rata nilai
        $rataRataNilai = HasilUjian::where('siswa_id', $siswa->id)
            ->where('status', 'graded')
            ->avg('nilai') ?? 0;
        
        // 5. Statistik per mapel (untuk chart)
        $mapelStats = DB::table('hasil_ujian')
            ->select('mata_pelajarans.nama as mapel_nama', DB::raw('AVG(hasil_ujian.nilai) as rata_rata'))
            ->join('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
            ->join('mata_pelajarans', 'ujian.mapel_id', '=', 'mata_pelajarans.id')
            ->where('hasil_ujian.siswa_id', $siswa->id)
            ->where('hasil_ujian.status', 'graded')
            ->groupBy('mata_pelajarans.nama')
            ->get();
        
        $mapelLabels = $mapelStats->pluck('mapel_nama')->toArray();
        $nilaiData = $mapelStats->pluck('rata_rata')->map(fn($v) => round($v, 1))->toArray();
        
        // 6. Riwayat ujian terbaru
        $riwayatUjian = HasilUjian::with(['ujian.mapel'])
            ->where('siswa_id', $siswa->id)
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();
        
        return view('siswa.dashboard', compact(
            'ujianAktif',
            'totalUjianAktif',
            'ujianDikerjakan',
            'rataRataNilai',
            'mapelLabels',
            'nilaiData',
            'riwayatUjian'
        ));
    }
}