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
    
    /**
     * ✅ HELPER: Ambil mapel yang bisa diakses guru ini
     * Rumus: Mapel milik guru + Mapel global (guru_id NULL)
     */
    private function getAccessibleMapel()
    {
        return MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })
            ->orderBy('nama')
            ->get();
    }
    
    public function index()
    {
        $ujian = Ujian::where('guru_id', Auth::id())
            ->with(['mapel', 'soalUjian'])
            ->latest()
            ->paginate(10);
        
        // ✅ FIX: Pakai helper untuk konsistensi
        $mapelList = $this->getAccessibleMapel();
        $kelasList = Kelas::orderBy('nama')->get();
        
        return view('ujian.index', compact('ujian', 'mapelList', 'kelasList'));
    }

    public function create()
    {
        // ✅ FIX: Ambil mapel milik guru + mapel global (SINKRON dengan /mapel)
        $mapelList = $this->getAccessibleMapel();
        $kelasList = Kelas::orderBy('nama')->get();
        
        return view('ujian.create', compact('mapelList', 'kelasList'));
    }
    
    public function store(Request $request)
    {
        // ✅ FIX: Validasi mapel harus bisa diakses guru ini
        $request->validate([
            'judul' => 'required|string|max:255',
            'mapel_id' => ['required', function($attribute, $value, $fail) {
                $exists = MataPelajaran::where('id', $value)
                    ->where(function($query) {
                        $query->where('guru_id', Auth::id())
                              ->orWhereNull('guru_id');
                    })->exists();
                
                if (!$exists) {
                    $fail('Mata pelajaran tidak valid atau bukan milik Anda.');
                }
            }],
            'mode' => 'required|in:latihan,ujian',
            'tipe' => 'required|in:quiz,uts,uas,latihan',
        ]);

        if ($request->mode === 'ujian') {
            $request->validate([
                'durasi_menit' => 'required|integer|min:1',
            ]);
        }

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
            'acak_soal' => $request->has('acak_soal') ? 1 : 0,
            'acak_opsi' => $request->has('acak_opsi') ? 1 : 0,
            'tampilkan_nilai' => $request->has('tampilkan_nilai') ? 1 : 0,
            'deteksi_tab_switch' => $request->has('deteksi_tab_switch') ? 1 : 0,
            'boleh_copy_paste' => $request->has('boleh_copy_paste') ? 1 : 0,
            'izinkan_upload_gambar_essay' => $request->has('izinkan_upload_gambar_essay') ? 1 : 0,
            'max_attempts' => $request->mode === 'ujian' ? ($request->max_attempts ?? 3) : 1,
        ]);

        // SIMPAN SOAL - PAKAI bank_soal_id (BUKAN soal_id)
        $jumlahSoal = 0;
        if ($request->has('soal_list') && is_array($request->soal_list)) {
            foreach ($request->soal_list as $soalData) {
                // Buat soal di bank soal
                $bankSoal = BankSoal::create([
                    'guru_id' => Auth::id(),
                    'mapel_id' => $request->mapel_id,
                    'tipe' => $soalData['tipe'] ?? 'pg',
                    'pertanyaan' => $soalData['pertanyaan'] ?? '',
                    'level' => $soalData['level'] ?? 'sedang',
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
                
                // Pakai bank_soal_id (BUKAN soal_id)
                $ujian->soalUjian()->create([
                    'bank_soal_id' => $bankSoal->id,
                    'bobot' => 1,
                ]);
                $jumlahSoal++;
            }
        }

        return redirect()->route('ujian.index')
            ->with('success', "Ujian berhasil dibuat dengan {$jumlahSoal} soal!");
    }
    
    public function destroy($id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $ujian->delete();
        
        return redirect()->route('ujian.index')->with('success', 'Ujian berhasil dihapus!');
    }

    public function getSoalByMapel($mapelId)
    {
        // ✅ FIX: Pastikan mapel bisa diakses guru ini
        $mapel = MataPelajaran::where('id', $mapelId)
            ->where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })
            ->first();
        
        if (!$mapel) {
            return response()->json(['error' => 'Mapel tidak valid'], 403);
        }
        
        $soal = BankSoal::where('mapel_id', $mapelId)
            ->where('status', 'published')
            ->where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })
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
    
    public function kerjakan($ujianId)
{
    $ujian = Ujian::findOrFail($ujianId);
    $siswa = Auth::user();
    
    // ✅ CEK MAX ATTEMPTS
    $jumlahPercobaan = HasilUjian::where('ujian_id', $ujianId)
        ->where('siswa_id', $siswa->id)
        ->count();
    
    if ($ujian->max_attempts > 0 && $jumlahPercobaan >= $ujian->max_attempts) {
        return redirect()->route('siswa.ujian.daftar')
            ->with('error', "Anda sudah mencapai batas maksimal percobaan ({$ujian->max_attempts}x).");
    }
    
    // Cek apakah sudah pernah mengerjakan dan status submitted
    $hasil = HasilUjian::where('ujian_id', $ujianId)
        ->where('siswa_id', $siswa->id)
        ->where('status', 'submitted')
        ->first();
    
    if ($hasil) {
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
    
    public function submitUjian(Request $request, $ujianId)
{
    $ujian = Ujian::findOrFail($ujianId);
    $siswa = Auth::user();
    
    $hasil = HasilUjian::where('ujian_id', $ujianId)
        ->where('siswa_id', $siswa->id)
        ->firstOrFail();
    
    // ✅ Hitung benar/salah/kosong
    $soalList = SoalUjian::where('ujian_id', $ujianId)->with('soal')->get();
    $benar = 0;
    $salah = 0;
    $kosong = 0;
    
    foreach ($soalList as $soalUjian) {
        $soal = $soalUjian->soal;
        $jawabanSiswa = $hasil->jawaban[$soal->id] ?? null;
        
        if (!$jawabanSiswa) {
            $kosong++;
        } elseif ($soal->tipe === 'pg') {
            if ($jawabanSiswa === $soal->jawaban) {
                $benar++;
            } else {
                $salah++;
            }
        } else {
            $kosong++; // Essay dianggap kosong untuk auto-grading
        }
    }
    
    $totalSoal = $soalList->count();
    $nilai = $totalSoal > 0 ? ($benar / $totalSoal) * 100 : 0;
    
    // ✅ Update hasil dengan data tracking
    $hasil->update([
        'status' => 'submitted',
        'benar' => $benar,
        'salah' => $salah,
        'kosong' => $kosong,
        'nilai' => $nilai,
        'submitted_at' => now(),
        // Tracking sudah tersimpan otomatis via JavaScript saat ujian
    ]);
    
    return redirect()->route('siswa.ujian.hasil', $ujianId);
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
    
public function hasil($ujianId)
{
    $ujian = Ujian::findOrFail($ujianId);
    $siswa = Auth::user();
    
    $hasil = HasilUjian::where('ujian_id', $ujianId)
        ->where('siswa_id', $siswa->id)
        ->firstOrFail();
    
    // ✅ Hitung waktu pengerjaan
    $waktuPengerjaan = null;
    if ($hasil->mulai_mengerjakan && $hasil->submitted_at) {
        $diff = \Carbon\Carbon::parse($hasil->mulai_mengerjakan)
            ->diff(\Carbon\Carbon::parse($hasil->submitted_at));
        $waktuPengerjaan = sprintf(
            '%d jam %d menit %d detik',
            $diff->h,
            $diff->i,
            $diff->s
        );
    }
    
    // ✅ Ambil soal untuk review
    $soalList = null;
    if ($ujian->tampilkan_nilai || $ujian->mode === 'latihan') {
        $soalQuery = SoalUjian::where('ujian_id', $ujianId)->with('soal');
        
        if ($ujian->acak_soal) {
            $soalQuery->inRandomOrder();
        } else {
            $soalQuery->orderBy('urutan');
        }
        
        $soalList = $soalQuery->get();
    }
    
    // ✅ PASTIKAN SEMUA VARIABEL DIKIRIM
    return view('siswa.ujian.hasil', compact(
        'ujian', 
        'hasil', 
        'soalList', 
        'waktuPengerjaan'
    ));
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
        
        // ✅ FIX: Pakai helper untuk konsistensi (mapel milik guru + global)
        $mapelList = $this->getAccessibleMapel();
        $kelasList = Kelas::orderBy('nama')->get();
        
        return view('ujian.edit', compact('ujian', 'mapelList', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        
        // ✅ FIX: Validasi mapel harus bisa diakses guru ini
        $rules = [
            'judul' => 'required|string|max:255',
            'mapel_id' => ['required', function($attribute, $value, $fail) {
                $exists = MataPelajaran::where('id', $value)
                    ->where(function($query) {
                        $query->where('guru_id', Auth::id())
                              ->orWhereNull('guru_id');
                    })->exists();
                
                if (!$exists) {
                    $fail('Mata pelajaran tidak valid.');
                }
            }],
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