<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalController extends Controller
{
    public function index(Request $request)
{
    $query = BankSoal::where('guru_id', Auth::id())->with('mapel');
    
    // Filter berdasarkan mapel
    if ($request->filled('mapel_id')) {
        $query->where('mapel_id', $request->mapel_id);
    }
    
    // Filter berdasarkan tipe
    if ($request->filled('tipe')) {
        $query->where('tipe', $request->tipe);
    }
    
    // Filter berdasarkan level
    if ($request->filled('level')) {
        $query->where('level', $request->level);
    }
    
    // Search berdasarkan pertanyaan
    if ($request->filled('search')) {
        $query->where('pertanyaan', 'like', '%' . $request->search . '%');
    }
    
    $soalList = $query->orderBy('created_at', 'desc')->paginate(12);
    
    // ✅ TAMBAHKAN VARIABLE INI
    $totalSoal = BankSoal::where('guru_id', Auth::id())->count();
    $totalPG = BankSoal::where('guru_id', Auth::id())->where('tipe', 'pg')->count();
    $totalEssay = BankSoal::where('guru_id', Auth::id())->where('tipe', 'essay')->count();
    
    // ✅ PENTING: Tambahkan $mapelList untuk filter dropdown
    $mapelList = \App\Models\MataPelajaran::orderBy('nama')->get();
    
    return view('bank-soal.index', compact(
        'soalList', 
        'totalSoal', 
        'totalPG', 
        'totalEssay',
        'mapelList'  // ✅ TAMBAHKAN INI
    ));
}

    public function create()
{
    // ✅ AMBIL SEMUA MAPEL (TANPA FILTER)
    $mapelList = \App\Models\MataPelajaran::orderBy('nama')->get();
    
    return view('bank-soal.create', compact('mapelList'));
}

    public function store(Request $request)
{
    $request->validate([
        'mapel_id' => 'required|exists:mata_pelajarans,id',
        'level' => 'required|in:mudah,sedang,sulit',
    ]);

    $created = 0;
    $updated = 0;
    
    $isEditMode = $request->has('edit_mode');
    $mapelIdEdit = $request->mapel_id_edit;
    
    // Jika edit mode, hapus soal yang tidak ada di list
    if ($isEditMode && $mapelIdEdit) {
        $updatedIds = [];
        if ($request->has('soal_list')) {
            foreach ($request->soal_list as $soalData) {
                if (!empty($soalData['id'])) {
                    $updatedIds[] = $soalData['id'];
                }
            }
        }
        
        BankSoal::where('guru_id', Auth::id())
            ->where('mapel_id', $mapelIdEdit)
            ->whereNotIn('id', $updatedIds)
            ->delete();
    }
    
    // Process soal list
    if ($request->has('soal_list') && is_array($request->soal_list)) {
        foreach ($request->soal_list as $soalData) {
            // ✅ FORMAT BENAR: opsi[A], opsi[B], opsi[C], opsi[D]
            $data = [
                'guru_id' => Auth::id(),
                'mapel_id' => $request->mapel_id,
                'tipe' => $soalData['tipe'] ?? 'pg',
                'pertanyaan' => $soalData['pertanyaan'] ?? '',
                'level' => $request->level,
                'status' => 'published',
                'opsi_a' => $soalData['opsi']['A'] ?? null,
                'opsi_b' => $soalData['opsi']['B'] ?? null,
                'opsi_c' => $soalData['opsi']['C'] ?? null,
                'opsi_d' => $soalData['opsi']['D'] ?? null,
                'jawaban' => $soalData['jawaban'] ?? null,
                'penjelasan' => $soalData['penjelasan'] ?? null,
                'opsi_a_tipe' => 'text',
                'opsi_b_tipe' => 'text',
                'opsi_c_tipe' => 'text',
                'opsi_d_tipe' => 'text',
            ];
            
            // Update jika ada ID, create jika tidak
            if (!empty($soalData['id'])) {
                BankSoal::where('id', $soalData['id'])
                    ->where('guru_id', Auth::id())
                    ->update($data);
                $updated++;
            } else {
                BankSoal::create($data);
                $created++;
            }
        }
    }

    if ($isEditMode) {
        $message = "✅ Berhasil update {$updated} soal!";
    } else {
        $message = "✅ Berhasil menyimpan {$created} soal ke Bank Soal!";
    }

    return redirect()->route('bank-soal.index')
        ->with('success', $message);
}
    public function edit($id)
{
    // Ambil soal yang diklik untuk tahu mapel-nya
    $soal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);
    
    // ✅ AMBIL SEMUA SOAL DARI MAPEL YANG SAMA
    $soalListExisting = BankSoal::where('guru_id', Auth::id())
        ->where('mapel_id', $soal->mapel_id)
        ->orderBy('created_at', 'asc')
        ->get();
    
    // Ambil semua mapel untuk dropdown
    $mapelList = \App\Models\MataPelajaran::orderBy('nama')->get();
    
    // ✅ REDIRECT KE CREATE DENGAN DATA SOAL
    return view('bank-soal.create', compact('mapelList', 'soalListExisting', 'soal'));
}

    public function update(Request $request, $id)
    {
        $soal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'level' => 'required|in:mudah,sedang,sulit',
        ]);

        $soal->update([
            'mapel_id' => $request->mapel_id,
            'level' => $request->level,
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'penjelasan' => $request->penjelasan,
        ]);

        return redirect()->route('bank-soal.index')
            ->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $soal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);
        $soal->delete();

        return redirect()->route('bank-soal.index')
            ->with('success', 'Soal berhasil dihapus!');
    }
}