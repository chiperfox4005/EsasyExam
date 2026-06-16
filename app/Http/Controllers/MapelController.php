<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapelController extends Controller
{
    public function index()
    {
        // ✅ TAMPILKAN: Mapel milik guru + Mapel global (guru_id NULL)
        $mapelList = MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })
            ->orderBy('nama')
            ->paginate(10);
        
        return view('mapel.index', compact('mapelList'));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        $gurus = User::where('role', 'guru')->get();
        return view('mapel.create', compact('kelasList', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:mata_pelajarans,kode',
            'deskripsi' => 'nullable|string',
            'icon' => 'required|string|max:50',
            'kelas_id' => 'nullable|exists:kelas,id',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        MataPelajaran::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon,
            'kelas_id' => $request->kelas_id ?: null,
            'guru_id' => $request->guru_id ?: null,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // ✅ CEK: Hanya bisa edit mapel milik sendiri atau global
        $mapel = MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })->findOrFail($id);
        
        $kelasList = Kelas::all();
        $gurus = User::where('role', 'guru')->get();

        return view('mapel.edit', compact('mapel', 'kelasList', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })->findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:mata_pelajarans,kode,' . $id,
            'deskripsi' => 'nullable|string',
            'icon' => 'required|string|max:50',
            'kelas_id' => 'nullable|exists:kelas,id',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon,
            'kelas_id' => $request->kelas_id ?: null,
            'guru_id' => $request->guru_id ?: null,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // ✅ CEK: Hanya bisa hapus mapel milik sendiri atau global
        $mapel = MataPelajaran::where(function($query) {
                $query->where('guru_id', Auth::id())
                      ->orWhereNull('guru_id');
            })->findOrFail($id);
        
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}