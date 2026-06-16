<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = User::where('role', 'siswa')->with('kelas')->latest()->paginate(10);
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        return view('siswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:users,nisn',
            'password' => 'required|min:6',
            'kelas_id' => 'nullable|exists:kelas,id',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'kelas_id' => $request->kelas_id ?: null,
            'phone' => $request->phone,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $kelasList = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:users,nisn,' . $id,
            'password' => 'nullable|min:6',
            'kelas_id' => 'nullable|exists:kelas,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $siswa = User::findOrFail($id);
        $data = [
            'name' => $request->name,
            'nisn' => $request->nisn,
            'kelas_id' => $request->kelas_id ?: null,
            'phone' => $request->phone,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::where('role', 'siswa')->findOrFail($id)->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}