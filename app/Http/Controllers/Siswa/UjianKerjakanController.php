<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianKerjakanController extends Controller
{
    /**
     * Mulai ujian
     */
    public function start(Ujian $ujian)
    {
        $siswaId = Auth::id();
        
        // Cek apakah sudah pernah mengerjakan
        $existingHasil = HasilUjian::where('ujian_id', $ujian->id)
            ->where('siswa_id', $siswaId)
            ->first();
        
        if ($existingHasil) {
            if ($existingHasil->status === 'completed') {
                return redirect()->route('siswa.ujian.hasil', $existingHasil->id)
                    ->with('info', 'Anda sudah mengerjakan ujian ini.');
            }
            
            // Lanjutkan ujian yang sedang berjalan
            return redirect()->route('siswa.ujian.kerjakan', $existingHasil->id);
        }
        
        // Cek max attempts
        if ($ujian->max_attempts > 0) {
            $attemptCount = HasilUjian::where('ujian_id', $ujian->id)
                ->where('siswa_id', $siswaId)
                ->count();
            
            if ($attemptCount >= $ujian->max_attempts) {
                return redirect()->route('siswa.ujian.daftar')
                    ->with('error', 'Anda sudah mencapai batas maksimal percobaan.');
            }
        }
        
        // Buat hasil ujian baru
        $hasilUjian = HasilUjian::create([
            'ujian_id' => $ujian->id,
            'siswa_id' => $siswaId,
            'status' => 'ongoing',
            'jawaban' => [],
            'started_at' => now(),
        ]);
        
        return redirect()->route('siswa.ujian.kerjakan', $hasilUjian->id);
    }
    
    /**
     * Tampilkan halaman pengerjaan
     */
    public function show(HasilUjian $hasilUjian)
    {
        // Pastikan siswa yang login adalah pemilik hasil ujian
        if ($hasilUjian->siswa_id !== Auth::id()) {
            abort(403);
        }
        
        // Cek status
        if ($hasilUjian->status === 'completed') {
            return redirect()->route('siswa.ujian.hasil', $hasilUjian->id);
        }
        
        $ujian = $hasilUjian->ujian;
        
        // Ambil soal-soal
        $soalUjianList = $ujian->soalUjian()
            ->with('bankSoal')
            ->get();
        
        // Acak soal jika diaktifkan
        if ($ujian->acak_soal) {
            $soalUjianList = $soalUjianList->shuffle()->values();
        }
        
        return view('siswa.ujian.kerjakan', compact('hasilUjian', 'ujian', 'soalUjianList'));
    }
    
    /**
     * Simpan jawaban (via AJAX)
     */
    public function saveAnswer(Request $request, HasilUjian $hasilUjian)
    {
        if ($hasilUjian->siswa_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'soal_id' => 'required|exists:soal_ujian,id',
            'jawaban' => 'nullable|string',
        ]);
        
        $jawaban = $hasilUjian->jawaban ?? [];
        $jawaban[$request->soal_id] = $request->jawaban;
        
        $hasilUjian->update([
            'jawaban' => $jawaban,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Submit ujian
     */
    public function submit(Request $request, HasilUjian $hasilUjian)
    {
        if ($hasilUjian->siswa_id !== Auth::id()) {
            abort(403);
        }
        
        $ujian = $hasilUjian->ujian;
        $jawabanSiswa = $hasilUjian->jawaban ?? [];
        
        // Hitung nilai
        $totalSoal = $ujian->soalUjian()->count();
        $jumlahBenar = 0;
        
        foreach ($ujian->soalUjian as $soalUjian) {
            $bankSoal = $soalUjian->bankSoal;
            $jawabanSiswaIni = $jawabanSiswa[$soalUjian->id] ?? null;
            
            if ($bankSoal->tipe === 'pg') {
                // Auto-grade untuk pilihan ganda
                if ($jawabanSiswaIni === $bankSoal->jawaban) {
                    $jumlahBenar++;
                }
            }
        }
        
        // Hitung nilai akhir
        $nilai = $totalSoal > 0 ? ($jumlahBenar / $totalSoal) * 100 : 0;
        
        // Update hasil ujian
        $hasilUjian->update([
            'status' => 'completed',
            'nilai' => round($nilai, 2),
            'jumlah_benar' => $jumlahBenar,
            'jumlah_salah' => $totalSoal - $jumlahBenar,
            'submitted_at' => now(),
        ]);
        
        return redirect()->route('siswa.ujian.hasil', $hasilUjian->id);
    }
}