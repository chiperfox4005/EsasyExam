<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PaketLatihan;
use App\Models\PaketLatihanSoal;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaketLatihanController extends Controller
{
    /**
     * List paket latihan guru
     */
    public function index()
    {
        $paketList = PaketLatihan::where('guru_id', Auth::id())
            ->with(['mapel', 'soal'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalPaket = PaketLatihan::where('guru_id', Auth::id())->count();
        $totalSoal = PaketLatihan::where('guru_id', Auth::id())->sum('total_soal');
        $mapelCount = PaketLatihan::where('guru_id', Auth::id())->distinct('mapel_id')->count('mapel_id');
        
        return view('guru.paket-latihan.index', compact('paketList', 'totalPaket', 'totalSoal', 'mapelCount'));
    }

    /**
     * Form buat paket baru
     */
    public function create()
    {
        $mapelList = MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })
            ->orderBy('nama')
            ->get();
        
        return view('guru.paket-latihan.create', compact('mapelList'));
    }

    /**
     * Simpan paket baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $paket = PaketLatihan::create([
            'guru_id' => Auth::id(),
            'mapel_id' => $request->mapel_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'total_soal' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('guru.paket-latihan.kelola-soal', $paket->id)
            ->with('success', 'Paket latihan berhasil dibuat! Silakan tambahkan soal.');
    }

    /**
     * Kelola soal dalam paket
     */
    public function kelolaSoal($id)
    {
        $paket = PaketLatihan::where('guru_id', Auth::id())->findOrFail($id);
        
        // Soal yang sudah ada di paket
        $soalPaketIds = PaketLatihanSoal::where('paket_latihan_id', $paket->id)
            ->pluck('bank_soal_id')
            ->toArray();
        
        $soalPaketList = BankSoal::whereIn('id', $soalPaketIds)
            ->with('mapel')
            ->orderBy('pertanyaan')
            ->get();
        
        // Bank soal yang bisa ditambahkan (mapel sama, belum ada di paket)
        $bankSoalList = BankSoal::where('mapel_id', $paket->mapel_id)
            ->where('status', 'published')
            ->whereNotIn('id', $soalPaketIds)
            ->orderBy('pertanyaan')
            ->get();
        
        return view('guru.paket-latihan.kelola-soal', compact('paket', 'soalPaketList', 'bankSoalList'));
    }

    /**
     * Tambah soal ke paket
     */
    public function tambahSoal(Request $request, $id)
    {
        $paket = PaketLatihan::where('guru_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soal,id',
        ]);

        // Cek apakah soal sudah ada
        $exists = PaketLatihanSoal::where('paket_latihan_id', $paket->id)
            ->where('bank_soal_id', $request->bank_soal_id)
            ->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Soal sudah ada dalam paket ini!');
        }

        $urutan = PaketLatihanSoal::where('paket_latihan_id', $paket->id)->count() + 1;
        
        PaketLatihanSoal::create([
            'paket_latihan_id' => $paket->id,
            'bank_soal_id' => $request->bank_soal_id,
            'urutan' => $urutan,
        ]);

        // Update total_soal
        $paket->update(['total_soal' => PaketLatihanSoal::where('paket_latihan_id', $paket->id)->count()]);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Hapus soal dari paket
     */
    public function hapusSoal($id, $soalId)
    {
        $paket = PaketLatihan::where('guru_id', Auth::id())->findOrFail($id);
        
        PaketLatihanSoal::where('paket_latihan_id', $paket->id)
            ->where('bank_soal_id', $soalId)
            ->delete();

        // Update total_soal
        $paket->update(['total_soal' => PaketLatihanSoal::where('paket_latihan_id', $paket->id)->count()]);

        return redirect()->back()->with('success', 'Soal berhasil dihapus dari paket!');
    }

    /**
     * Edit info paket
     */
    public function edit($id)
    {
        $paket = PaketLatihan::where('guru_id', Auth::id())->findOrFail($id);
        $mapelList = MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })
            ->orderBy('nama')
            ->get();
        
        return view('guru.paket-latihan.edit', compact('paket', 'mapelList'));
    }

    /**
     * Update paket
     */
    public function update(Request $request, $id)
    {
        $paket = PaketLatihan::where('guru_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $paket->update([
            'mapel_id' => $request->mapel_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('guru.paket-latihan.index')
            ->with('success', 'Paket latihan berhasil diperbarui!');
    }

    /**
     * Hapus paket
     */
    public function destroy($id)
    {
        $paket = PaketLatihan::where('guru_id', Auth::id())->findOrFail($id);
        $paket->delete();
        
        return redirect()->route('guru.paket-latihan.index')
            ->with('success', 'Paket latihan berhasil dihapus!');
    }
}