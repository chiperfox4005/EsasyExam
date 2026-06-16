<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\PaketLatihan;
use App\Models\LatihanHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LatihanController extends Controller
{
    /**
     * List Paket Latihan per Mapel
     */
    public function index(Request $request)
    {
        $siswa = Auth::user();
        
        // Ambil semua mapel yang punya paket latihan
        $mapelList = MataPelajaran::withCount(['paketLatihan' => function($query) {
                $query->where('is_active', true);
            }])
            ->having('paket_latihan_count', '>', 0)
            ->orderBy('nama')
            ->get();
        
        // Filter by mapel
        $mapelId = $request->mapel_id;
        
        $query = PaketLatihan::where('is_active', true)
            ->with(['mapel', 'guru', 'soal']);
        
        if ($mapelId) {
            $query->where('mapel_id', $mapelId);
        }
        
        $paketList = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('siswa.latihan.index', compact('paketList', 'mapelList', 'mapelId'));
    }
    
    /**
     * Kerjakan paket latihan
     */
    public function kerjakan($paketId)
    {
        $siswa = Auth::user();
        
        $paket = PaketLatihan::where('id', $paketId)
            ->where('is_active', true)
            ->with(['mapel', 'soal'])
            ->firstOrFail();
        
        // Hitung percobaan berikutnya
        $jumlahPercobaan = LatihanHistory::where('siswa_id', $siswa->id)
            ->where('paket_latihan_id', $paketId)
            ->count();
        
        $percobaanKe = ($jumlahPercobaan % 10) + 1; // Reset ke 1 jika sudah 10x
        
        // Acak soal jika diminta
        $soalList = $paket->soal;
        
        return view('siswa.latihan.kerjakan', compact('paket', 'soalList', 'percobaanKe'));
    }
    
    /**
     * Submit latihan dan tampilkan hasil
     */
    public function submit(Request $request)
    {
        $siswa = Auth::user();
        $jawaban = $request->input('jawaban', []);
        $paketId = $request->input('paket_id');
        
        $paket = PaketLatihan::findOrFail($paketId);
        $soalList = $paket->soal->keyBy('id');
        
        // Hitung hasil
        $totalSoal = $soalList->count();
        $benar = 0;
        $salah = 0;
        $kosong = 0;
        $detailHasil = [];
        
        foreach ($soalList as $soal) {
            $jawabanSiswa = $jawaban[$soal->id] ?? null;
            
            if (!$jawabanSiswa) {
                $kosong++;
                $isCorrect = false;
            } elseif ($soal->tipe === 'pg') {
                $isCorrect = ($jawabanSiswa === $soal->jawaban);
                if ($isCorrect) {
                    $benar++;
                } else {
                    $salah++;
                }
            } else {
                // Essay
                $isCorrect = null;
                $kosong++;
            }
            
            $detailHasil[] = [
                'soal' => $soal,
                'jawaban_siswa' => $jawabanSiswa,
                'is_correct' => $isCorrect,
            ];
        }
        
        $nilai = $totalSoal > 0 ? ($benar / $totalSoal) * 100 : 0;
        
        // ✅ SIMPAN KE HISTORI
        $jumlahPercobaan = LatihanHistory::where('siswa_id', $siswa->id)
            ->where('paket_latihan_id', $paketId)
            ->count();
        
        $percobaanKe = ($jumlahPercobaan % 10) + 1; // Reset ke 1 jika sudah 10x
        
        // Jika sudah ada percobaan ke-N (karena reset), hapus yang lama
        $existingHistory = LatihanHistory::where('siswa_id', $siswa->id)
            ->where('paket_latihan_id', $paketId)
            ->where('percobaan_ke', $percobaanKe)
            ->first();
        
        if ($existingHistory) {
            $existingHistory->delete();
        }
        
        // Simpan histori baru
        $history = LatihanHistory::create([
            'siswa_id' => $siswa->id,
            'paket_latihan_id' => $paketId,
            'percobaan_ke' => $percobaanKe,
            'total_soal' => $totalSoal,
            'benar' => $benar,
            'salah' => $salah,
            'kosong' => $kosong,
            'nilai' => $nilai,
            'jawaban' => $jawaban,
            'mulai_at' => now()->subMinutes(30), // Estimasi
            'selesai_at' => now(),
        ]);
        
        return view('siswa.latihan.hasil', compact(
            'paket', 'detailHasil', 'totalSoal', 'benar', 'salah', 'kosong', 'nilai', 'history'
        ));
    }
    
    /**
     * ✅ BARU: Lihat histori pengerjaan
     */
    public function histori($paketId)
    {
        $siswa = Auth::user();
        
        $paket = PaketLatihan::findOrFail($paketId);
        
        $historiList = LatihanHistory::where('siswa_id', $siswa->id)
            ->where('paket_latihan_id', $paketId)
            ->orderBy('percobaan_ke', 'asc')
            ->get();
        
        // Stats
        $totalPercobaan = $historiList->count();
        $rataRataNilai = $historiList->avg('nilai') ?? 0;
        $nilaiTertinggi = $historiList->max('nilai') ?? 0;
        $nilaiTerendah = $historiList->min('nilai') ?? 0;
        
        return view('siswa.latihan.histori', compact('paket', 'historiList', 'totalPercobaan', 'rataRataNilai', 'nilaiTertinggi', 'nilaiTerendah'));
    }
}