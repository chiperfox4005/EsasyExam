<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $stats = [
            'totalSiswa' => User::where('role', 'siswa')->count(),
            'totalGuru' => User::where('role', 'guru')->count(),
            'totalUjian' => Ujian::count(),
            'totalSoal' => DB::table('bank_soal')->count(),
            'totalMapel' => MataPelajaran::count(),
            'totalHasil' => HasilUjian::count(),
        ];
        
        // Rata-rata nilai keseluruhan
        $stats['rataRataNilai'] = HasilUjian::where('status', 'graded')->avg('nilai') ?? 0;
        
        // Ujian aktif
        $stats['ujianAktif'] = Ujian::where('status', 'published')
            ->where('mulai_at', '<=', now())
            ->where('selesai_at', '>=', now())
            ->count();
        
        // Chart: User per bulan (6 bulan terakhir)
        $userPerBulan = DB::table('users')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw("SUM(CASE WHEN role = 'siswa' THEN 1 ELSE 0 END) as siswa"),
                DB::raw("SUM(CASE WHEN role = 'guru' THEN 1 ELSE 0 END) as guru")
            )
            ->where('created_at', '>=', now()->subMonths(5))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        $labelsBulan = $userPerBulan->map(fn($r) => \Carbon\Carbon::parse($r->bulan . '-01')->format('M Y'))->toArray();
        $dataSiswa = $userPerBulan->pluck('siswa')->map(fn($v) => (int)$v)->toArray();
        $dataGuru = $userPerBulan->pluck('guru')->map(fn($v) => (int)$v)->toArray();
        
        // Top 5 Mapel berdasarkan rata-rata nilai
        $topMapel = DB::table('hasil_ujian')
            ->select('mata_pelajarans.nama as mapel_nama', DB::raw('AVG(hasil_ujian.nilai) as rata_rata'))
            ->join('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
            ->join('mata_pelajarans', 'ujian.mapel_id', '=', 'mata_pelajarans.id')
            ->where('hasil_ujian.status', 'graded')
            ->groupBy('mata_pelajarans.nama')
            ->orderByDesc('rata_rata')
            ->take(5)
            ->get();
        
        // Ujian terbaru
        $ujianTerbaru = Ujian::with(['mapel', 'guru'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'stats',
            'labelsBulan',
            'dataSiswa',
            'dataGuru',
            'topMapel',
            'ujianTerbaru'
        ));
    }
}