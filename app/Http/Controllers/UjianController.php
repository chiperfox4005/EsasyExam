<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\SoalUjian;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UjianController extends Controller
{
    // ========== GURU & ADMIN ==========
    
    public function index()
    {
        $ujian = Ujian::where('guru_id', Auth::id())
            ->with(['mapel', 'soalUjian'])
            ->latest()
            ->paginate(10);
            
        $mapelList = MataPelajaran::where('guru_id', Auth::id())->get();
        if ($mapelList->isEmpty()) {
            $mapelList = MataPelajaran::all();
        }
        
        $kelasList = Kelas::all();
        
        return view('ujian.index', compact('ujian', 'mapelList', 'kelasList'));
    }

    public function create()
{
    // ✅ AMBIL SEMUA MAPEL UNTUK GURU
    $mapelList = MataPelajaran::where('guru_id', Auth::id())
        ->orderBy('nama')
        ->get();
    
    // ✅ AMBIL SEMUA KELAS
    $kelasList = Kelas::orderBy('nama')->get();
    
    return view('ujian.create', compact('mapelList', 'kelasList'));
}

    public function store(Request $request)
{
    $rules = [
        'judul' => 'required|string|max:255',
        'mapel_id' => 'required|exists:mata_pelajarans,id',
        'mode' => 'required|in:latihan,ujian',
        'tipe' => 'required|in:quiz,uts,uas,latihan',
    ];

    if ($request->mode === 'ujian') {
        $rules['durasi_menit'] = 'required|integer|min:1';
    }

    $request->validate($rules);

    // Create ujian
    $ujian = Ujian::create([
        'guru_id' => Auth::id(),
        'mapel_id' => $request->mapel_id,
        'kelas_id' => $request->kelas_id ?: null,
        'judul' => $request->judul,
        'tipe' => $request->tipe,
        'mode' => $request->mode,
        'durasi_menit' => $request->mode === 'ujian' ? ($request->durasi_menit ?? 60) : 0,
        'mulai_at' => $request->mode === 'ujian' ? ($request->mulai_at ?? now()) : now(),
        'selesai_at' => $request->mode === 'ujian' ? ($request->selesai_at ?? now()->addDays(7)) : now()->addYear(),
        'status' => 'published',
        'acak_soal' => $request->has('acak_soal'),
        'acak_opsi' => $request->has('acak_opsi'),
        'tampilkan_nilai' => $request->has('tampilkan_nilai'),
        'deteksi_tab_switch' => $request->has('deteksi_tab_switch'),
        'boleh_copy_paste' => $request->has('boleh_copy_paste'),
        'izinkan_upload_gambar_essay' => $request->has('izinkan_upload_gambar_essay'),
        'max_attempts' => $request->mode === 'ujian' ? ($request->max_attempts ?? 3) : 1,
    ]);

    // 🔥 SIMPAN SOAL DARI FORM (FORMAT BARU)
    if ($request->has('soal_list') && is_array($request->soal_list)) {
        foreach ($request->soal_list as $soalData) {
            // Buat soal di bank soal dulu
            $bankSoal = BankSoal::create([
                'guru_id' => Auth::id(),
                'mapel_id' => $request->mapel_id,
                'tipe' => $soalData['tipe'],
                'pertanyaan' => $soalData['pertanyaan'],
                'level' => $soalData['level'],
                'status' => 'published',
                'opsi_a' => $soalData['opsi']['A'] ?? null,
                'opsi_b' => $soalData['opsi']['B'] ?? null,
                'opsi_c' => $soalData['opsi']['C'] ?? null,
                'opsi_d' => $soalData['opsi']['D'] ?? null,
                'jawaban' => $soalData['jawaban'] ?? null,
                'opsi_a_tipe' => 'text',
                'opsi_b_tipe' => 'text',
                'opsi_c_tipe' => 'text',
                'opsi_d_tipe' => 'text',
            ]);
            
            // Link ke ujian
            $ujian->soalUjian()->create([
                'soal_id' => $bankSoal->id,
                'bobot' => 1,
            ]);
        }
    }

    return redirect()->route('ujian.index')
        ->with('success', 'Ujian berhasil dibuat dengan ' . count($request->soal_list ?? []) . ' soal!');
}
    public function destroy($id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $ujian->delete();
        
        return redirect()->route('ujian.index')->with('success', 'Ujian berhasil dihapus!');
    }

    public function getSoalByMapel($mapelId)
    {
        $soal = BankSoal::where('mapel_id', $mapelId)
            ->where('status', 'published')
            ->where('guru_id', Auth::id())
            ->get();
            
        return response()->json($soal);
    }

    // ========== SISWA ==========
    
    // Daftar ujian untuk siswa
    public function daftarUjian()
{
    $siswa = Auth::user();
    $now = now();
    
    // Ambil semua ujian yang tersedia untuk siswa ini
    $ujianList = Ujian::with(['mapel', 'soalUjian'])
        ->where('status', 'published')
        ->where('mulai_at', '<=', $now)
        ->where('selesai_at', '>=', $now)
        ->where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')
              ->orWhere('kelas_id', $siswa->kelas_id);
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($ujian) use ($siswa) {
            // Cek apakah siswa sudah mengerjakan
            $hasil = HasilUjian::where('ujian_id', $ujian->id)
                ->where('siswa_id', $siswa->id)
                ->first();
            
            $ujian->sudah_dikerjakan = $hasil !== null;
            $ujian->rata_rata_nilai = $hasil ? $hasil->nilai : null;
            
            return $ujian;
        });
    
    return view('siswa.ujian.daftar', compact('ujianList'));
}
    // Mengerjakan ujian
    public function kerjakan($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $siswa = Auth::user();
        
        // Cek apakah sudah pernah mengerjakan
        $hasil = HasilUjian::where('ujian_id', $ujianId)
            ->where('siswa_id', $siswa->id)
            ->first();
        
        if ($hasil && $hasil->status === 'submitted') {
            return redirect()->route('siswa.ujian.hasil', $ujianId);
        }
        
        // Ambil soal dengan pengacakan jika diaktifkan
        $soalQuery = SoalUjian::where('ujian_id', $ujianId)->with('soal');
        
        if ($ujian->acak_soal) {
            $soalQuery->inRandomOrder();
        } else {
            $soalQuery->orderBy('urutan');
        }
        
        $soalList = $soalQuery->get();
        
        // Jika belum ada record hasil, buat baru
        if (!$hasil) {
            $hasil = HasilUjian::create([
                'ujian_id' => $ujianId,
                'siswa_id' => $siswa->id,
                'mulai_mengerjakan' => now(),
                'status' => 'ongoing'
            ]);
        }
        
        // Hitung waktu sisa
        $waktuSisa = 0;
        if ($ujian->mode === 'ujian') {
            $endTime = Carbon::parse($hasil->mulai_mengerjakan)->addMinutes($ujian->durasi_menit);
            $waktuSisa = max(0, now()->diffInSeconds($endTime));
        }
        
        return view('siswa.ujian.kerjakan', compact('ujian', 'soalList', 'hasil', 'waktuSisa'));
    }
    
    // Simpan jawaban
    public function simpanJawaban(Request $request, $ujianId)
{
    $ujian = Ujian::findOrFail($ujianId);
    $siswa = Auth::user();
    
    $hasil = HasilUjian::where('ujian_id', $ujianId)
        ->where('siswa_id', $siswa->id)
        ->firstOrFail();
    
    $jawaban = $hasil->jawaban ?? [];
    
    // Merge jawaban PG baru
    if ($request->has('jawaban')) {
        foreach ($request->jawaban as $soalId => $nilai) {
            $jawaban[$soalId] = $nilai;
        }
    }
    
    // Handle upload gambar essay
    if ($request->hasFile('jawaban_gambar')) {
        $gambarPaths = $hasil->jawaban_gambar ?? [];
        
        foreach ($request->jawaban_gambar as $soalId => $file) {
            if ($file->isValid()) {
                $path = $file->store('jawaban-essay', 'public');
                $gambarPaths[$soalId] = $path;
            }
        }
        
        $hasil->jawaban_gambar = $gambarPaths;
    }
    
    $hasil->update(['jawaban' => $jawaban]);
    
    return response()->json(['success' => true]);
}
    // Submit ujian
    public function submitUjian(Request $request, $ujianId)
{
    $ujian = Ujian::findOrFail($ujianId);
    $siswa = Auth::user();
    
    // Cek apakah siswa sudah mengerjakan
    $hasil = HasilUjian::where('ujian_id', $ujianId)
        ->where('siswa_id', $siswa->id)
        ->first();
    
    if (!$hasil) {
        return redirect()->route('siswa.ujian.daftar')
            ->with('error', 'Anda belum mengerjakan ujian ini.');
    }
    
    // Update tracking data
    $hasil->update([
        'submitted_at' => now(),
        'status' => 'submitted',
        'tab_switch_count' => $request->tab_switch_count ?? 0,
        'copy_count' => $request->copy_count ?? 0,
        'paste_count' => $request->paste_count ?? 0,
        'right_click_count' => $request->right_click_count ?? 0,
        'blur_count' => $request->blur_count ?? 0,
    ]);
    
    // Auto-grading
    $this->gradeUjian($ujian, $hasil);
    
    // 🔥 PENTING: Redirect ke route SISWA, bukan guru!
    return redirect()->route('siswa.ujian.hasil', ['ujianId' => $ujianId])
        ->with('success', 'Ujian berhasil dikumpulkan!');
}
    // Auto-grading
    private function gradeUjian($ujian, $hasil)
    {
        $soalList = SoalUjian::where('ujian_id', $ujian->id)
            ->with('soal')
            ->get();
        
        $benar = 0;
        $salah = 0;
        $kosong = 0;
        $totalSoalPG = 0;
        
        $jawaban = $hasil->jawaban ?? [];
        
        foreach ($soalList as $item) {
            $soal = $item->soal;
            
            if ($soal->tipe !== 'pg') continue;
            
            $totalSoalPG++;
            $jawabanSiswa = $jawaban[$soal->id] ?? null;
            
            if (!$jawabanSiswa) {
                $kosong++;
            } elseif ($jawabanSiswa === $soal->jawaban) {
                $benar++;
            } else {
                $salah++;
            }
        }
        
        $nilai = $totalSoalPG > 0 ? ($benar / $totalSoalPG) * 100 : 0;
        
        $hasil->update([
            'benar' => $benar,
            'salah' => $salah,
            'kosong' => $kosong,
            'nilai' => $nilai,
            'status' => 'graded'
        ]);
    }
    
    // Lihat hasil
    public function hasil($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $siswa = Auth::user();
        
        $hasil = HasilUjian::where('ujian_id', $ujianId)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();
        
        // Ambil soal untuk review (jika diizinkan)
        $soalList = null;
        if ($ujian->tampilkan_nilai || $ujian->mode === 'latihan') {
            $soalQuery = SoalUjian::where('ujian_id', $ujianId)->with('soal');
            $soalList = $soalQuery->get();
        }
        
        return view('siswa.ujian.hasil', compact('ujian', 'hasil', 'soalList'));
    }
    
    // ========== HELPER METHODS ==========
    
    /**
     * Helper untuk mengacak opsi jawaban A-E
     * Digunakan saat ujian memiliki setting acak_opsi = true
     * 
     * @param BankSoal $soal
     * @return array Array opsi yang sudah diacak dengan label baru
     */
    public function acakOpsi($soal)
    {
        $opsi = [];
        $labels = ['A', 'B', 'C', 'D', 'E'];
        
        // Kumpulkan semua opsi yang tidak kosong
        foreach ($labels as $label) {
            $key = 'opsi_' . strtolower($label);
            if ($soal->$key) {
                $opsi[] = [
                    'label' => $label,
                    'value' => $soal->$key,
                    'is_correct' => $soal->jawaban === $label
                ];
            }
        }
        
        // Acak urutan opsi
        shuffle($opsi);
        
        // Re-label opsi setelah diacak (A, B, C, D, E)
        foreach ($opsi as $index => &$opsiItem) {
            $opsiItem['new_label'] = $labels[$index];
        }
        
        return $opsi;
    }
    public function riwayat()
{
    $siswa = Auth::user();
    
    $hasilList = HasilUjian::where('siswa_id', $siswa->id)
        ->with('ujian.mapel')
        ->latest()
        ->paginate(15);
    
    return view('siswa.riwayat', compact('hasilList'));
}

public function edit($id)
{
    $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
    $mapelList = MataPelajaran::where('guru_id', Auth::id())->get();
    $kelasList = Kelas::all();
    
    return view('ujian.edit', compact('ujian', 'mapelList', 'kelasList'));
}

public function update(Request $request, $id)
{
    $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
    
    $rules = [
        'judul' => 'required|string|max:255',
        'mapel_id' => 'required|exists:mata_pelajarans,id',
        'mode' => 'required|in:latihan,ujian',
    ];

    if ($request->mode === 'ujian') {
        $rules['durasi_menit'] = 'required|integer|min:1';
        $rules['mulai_at'] = 'required|date';
        $rules['selesai_at'] = 'required|date|after:mulai_at';
    }

    $request->validate($rules);

    $ujian->update([
        'judul' => $request->judul,
        'mapel_id' => $request->mapel_id,
        'kelas_id' => $request->kelas_id ?: null,
        'durasi_menit' => $request->mode === 'ujian' ? $request->durasi_menit : 0,
        'mulai_at' => $request->mode === 'ujian' ? $request->mulai_at : now(),
        'selesai_at' => $request->mode === 'ujian' ? $request->selesai_at : now()->addYear(),
        'tipe' => $request->tipe ?? 'quiz',
        'mode' => $request->mode,
        'status' => $request->status ?? 'published',
        'acak_soal' => $request->has('acak_soal'),
        'acak_opsi' => $request->has('acak_opsi'),
        'tampilkan_nilai' => $request->has('tampilkan_nilai'),
        'boleh_copy_paste' => $request->has('boleh_copy_paste'),
        'deteksi_tab_switch' => $request->has('deteksi_tab_switch'),
    ]);

    return redirect()->route('ujian.index')->with('success', 'Ujian berhasil diperbarui!');
}
}