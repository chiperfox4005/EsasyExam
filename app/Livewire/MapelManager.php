<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MapelManager extends Component
{
    use WithPagination;

    public $nama = '', $kode = '', $deskripsi = '', $icon = 'fa-book', $kelas_id = '', $guru_id = '';
    public $editingId = null;
    public $search = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'kode' => 'required|string|max:50|unique:mata_pelajarans,kode',
        'deskripsi' => 'nullable|string',
        'icon' => 'required|string|max:50',
        'kelas_id' => 'nullable|exists:kelas,id',
        'guru_id' => 'nullable|exists:users,id',
    ];

    public function render()
    {
        $mapel = MataPelajaran::with(['kelas', 'guru'])
            ->when($this->search, function($query) {
                $query->where('nama', 'like', "%{$this->search}%")
                      ->orWhere('kode', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        $kelasList = Kelas::all();
        $gurus = User::where('role', 'guru')->get();

        return view('livewire.mapel-manager', compact('mapel', 'kelasList', 'gurus'))
            ->layout('layouts.app');
    }

    public function store()
    {
        $this->validate();

        MataPelajaran::create([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi,
            'icon' => $this->icon,
            'kelas_id' => $this->kelas_id ?: null,
            'guru_id' => $this->guru_id ?: null,
        ]);

        session()->flash('success', 'Mata pelajaran berhasil ditambahkan!');
        $this->resetForm();
    }

    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $this->editingId = $id;
        $this->nama = $mapel->nama;
        $this->kode = $mapel->kode;
        $this->deskripsi = $mapel->deskripsi;
        $this->icon = $mapel->icon;
        $this->kelas_id = $mapel->kelas_id;
        $this->guru_id = $mapel->guru_id;
    }

    public function update()
    {
        $this->validate([
            'kode' => 'required|string|max:50|unique:mata_pelajarans,kode,' . $this->editingId,
        ]);

        $mapel = MataPelajaran::findOrFail($this->editingId);
        $mapel->update([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi,
            'icon' => $this->icon,
            'kelas_id' => $this->kelas_id ?: null,
            'guru_id' => $this->guru_id ?: null,
        ]);

        session()->flash('success', 'Mata pelajaran berhasil diperbarui!');
        $this->resetForm();
    }

    public function delete($id)
    {
        MataPelajaran::findOrFail($id)->delete();
        session()->flash('success', 'Mata pelajaran berhasil dihapus!');
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->kode = '';
        $this->deskripsi = '';
        $this->icon = 'fa-book';
        $this->kelas_id = '';
        $this->guru_id = '';
        $this->editingId = null;
    }
}