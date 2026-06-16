<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\HasilUjian;
use Illuminate\Support\Facades\Auth;

class UjianHasilController extends Controller
{
    /**
     * Tampilkan hasil ujian
     */
    public function show(HasilUjian $hasilUjian)
    {
        // Pastikan siswa yang login adalah pemilik hasil ujian
        if ($hasilUjian->siswa_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        // Pastikan ujian sudah selesai
        if ($hasilUjian->status !== 'completed') {
            return redirect()->route('siswa.ujian.kerjakan', $hasilUjian->id)
                ->with('error', 'Ujian belum selesai dikerjakan.');
        }
        
        $ujian = $hasilUjian->ujian;
        
        // Ambil semua soal dengan jawaban yang benar
        $soalUjianList = $ujian->soalUjian()
            ->with('bankSoal')
            ->get();
        
        // Parse jawaban siswa
        $jawabanSiswa = $hasilUjian->jawaban ?? [];
        
        return view('siswa.ujian.hasil', compact('hasilUjian', 'ujian', 'soalUjianList', 'jawabanSiswa'));
    }
}