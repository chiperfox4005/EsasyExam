<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\NilaiExport;
use Maatwebsite\Excel\Facades\Excel;

class HasilUjianController extends Controller
{
    // Guru: Lihat semua hasil ujian tertentu
    public function indexByUjian($ujianId)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($ujianId);
        
        $hasilList = HasilUjian::where('ujian_id', $ujianId)
            ->with('siswa')
            ->orderBy('nilai', 'desc')
            ->paginate(20);
        
        return view('guru.hasil-ujian.index', compact('ujian', 'hasilList'));
    }

    // Guru: Lihat detail jawaban siswa
    public function detail($ujianId, $siswaId)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($ujianId);
        $siswa = User::findOrFail($siswaId);
        
        $hasil = HasilUjian::where('ujian_id', $ujianId)
            ->where('siswa_id', $siswaId)
            ->with('soalUjian.soal')
            ->firstOrFail();
        
        return view('guru.hasil-ujian.detail', compact('ujian', 'siswa', 'hasil'));
    }

    // Guru: Export hasil ujian ke Excel (Bawaan sebelumnya)
    public function export($ujianId)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($ujianId);
        
        $hasilList = HasilUjian::where('ujian_id', $ujianId)
            ->with('siswa')
            ->orderBy('nilai', 'desc')
            ->get();
        
        // TODO: Implement export to Excel
        return response()->json([
            'message' => 'Export feature coming soon',
            'data' => $hasilList
        ]);
    }

    // Admin: Lihat semua hasil ujian
    public function adminIndex()
    {
        $hasilList = HasilUjian::with(['ujian.mapel', 'siswa'])
            ->latest()
            ->paginate(20);
        
        return view('admin.hasil-ujian.index', compact('hasilList'));
    }

    /**
     * Rekap nilai untuk satu ujian
     */
    public function rekap($ujianId)
    {
        $ujian = \App\Models\Ujian::where('guru_id', Auth::id())->findOrFail($ujianId);
        
        $hasilList = HasilUjian::with(['siswa.kelas'])
            ->where('ujian_id', $ujianId)
            ->where('status', 'graded')
            ->orderBy('nilai', 'desc')
            ->get();
        
        // Statistik
        $totalSiswa = $hasilList->count();
        $rataRata = $hasilList->avg('nilai') ?? 0;
        $nilaiTertinggi = $hasilList->max('nilai') ?? 0;
        $nilaiTerendah = $hasilList->min('nilai') ?? 0;
        $lulus = $hasilList->where('nilai', '>=', 75)->count();
        $tidakLulus = $totalSiswa - $lulus;
        
        return view('guru.ujian.rekap', compact('ujian', 'hasilList', 'totalSiswa', 'rataRata', 'nilaiTertinggi', 'nilaiTerendah', 'lulus', 'tidakLulus'));
    }

    /**
     * Export nilai ke Excel
     */
    public function exportExcel($ujianId)
    {
        $ujian = \App\Models\Ujian::where('guru_id', Auth::id())->findOrFail($ujianId);
        
        return Excel::download(new NilaiExport($ujianId), "Rekap_Nilai_{$ujian->judul}_" . date('Y-m-d') . '.xlsx');
    }
}