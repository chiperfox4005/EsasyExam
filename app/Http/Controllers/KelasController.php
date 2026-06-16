<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->latest()->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|integer|min:1|max:12',
            'tipe' => 'required|in:formal,virtual',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        Kelas::create([
            'nama' => $request->nama,
            'tingkat' => $request->tingkat,
            'tipe' => $request->tipe,
            'wali_kelas_id' => $request->wali_kelas_id ?: null,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $gurus = User::where('role', 'guru')->get();
        return view('kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|integer|min:1|max:12',
            'tipe' => 'required|in:formal,virtual',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama' => $request->nama,
            'tingkat' => $request->tingkat,
            'tipe' => $request->tipe,
            'wali_kelas_id' => $request->wali_kelas_id ?: null,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Kelas::findOrFail($id)->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}