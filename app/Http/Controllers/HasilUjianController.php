<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Guru: Export hasil ujian ke Excel
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
}