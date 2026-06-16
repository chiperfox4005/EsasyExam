<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();

        // 1. Statistik Utama
        $totalUjian = Ujian::where('guru_id', $guruId)->count();
        
        $totalPeserta = HasilUjian::whereIn('ujian_id', function($query) use ($guruId) {
                $query->select('id')->from('ujian')->where('guru_id', $guruId);
            })->count();
            
        $rataRataNilai = HasilUjian::whereIn('ujian_id', function($query) use ($guruId) {
                $query->select('id')->from('ujian')->where('guru_id', $guruId);
            })->where('status', 'graded')->avg('nilai') ?? 0;

        // 2. Ujian Terbaru dengan Statistik
        $ujianTerbaru = Ujian::where('guru_id', $guruId)
            ->with(['mapel', 'hasilUjian'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($ujian) {
                $ujian->jumlah_peserta = $ujian->hasilUjian->count();
                $ujian->rata_rata = $ujian->hasilUjian->where('status', 'graded')->avg('nilai') ?? 0;
                return $ujian;
            });

        // 3. Data untuk Grafik (Rata-rata Nilai per Mapel)
        $chartData = DB::table('ujian')
            ->join('hasil_ujian', 'ujian.id', '=', 'hasil_ujian.ujian_id')
            ->join('mata_pelajarans', 'ujian.mapel_id', '=', 'mata_pelajarans.id')
            ->where('ujian.guru_id', $guruId)
            ->where('hasil_ujian.status', 'graded')
            ->select('mata_pelajarans.nama as mapel', DB::raw('AVG(hasil_ujian.nilai) as rata_rata'))
            ->groupBy('mata_pelajarans.nama')
            ->get();

        $mapelLabels = $chartData->pluck('mapel')->toArray();
        $nilaiData = $chartData->pluck('rata_rata')->map(fn($val) => round($val, 1))->toArray();

        return view('guru.dashboard', compact(
            'totalUjian', 
            'totalPeserta', 
            'rataRataNilai',
            'ujianTerbaru', 
            'mapelLabels', 
            'nilaiData'
        ));
    }
}