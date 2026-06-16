<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use App\Models\User;

class KelasManager extends Component
{
    use WithPagination;

    public $nama, $tingkat, $tipe = 'formal', $wali_kelas_id, $editingId;
    public $search = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'tingkat' => 'required|integer|min:1|max:12',
        'tipe' => 'required|in:formal,virtual',
        'wali_kelas_id' => 'nullable|exists:users,id',
    ];

    public function render()
    {
        $gurus = User::where('role', 'guru')->get();
        $kelas = Kelas::where('nama', 'like', "%{$this->search}%")
            ->orderBy('tingkat', 'asc')
            ->paginate(10);

        return view('livewire.kelas-manager', compact('kelas', 'gurus'))
            ->layout('layouts.app');
    }

    public function resetForm()
    {
        $this->nama = ''; $this->tingkat = ''; $this->tipe = 'formal';
        $this->wali_kelas_id = null; $this->editingId = null;
    }

    public function store()
    {
        $this->validate();
        Kelas::create($this->only(['nama', 'tingkat', 'tipe', 'wali_kelas_id']));
        $this->resetForm();
        session()->flash('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $this->editingId = $id;
        $this->nama = $kelas->nama;
        $this->tingkat = $kelas->tingkat;
        $this->tipe = $kelas->tipe;
        $this->wali_kelas_id = $kelas->wali_kelas_id;
    }

    public function update()
    {
        $this->validate();
        Kelas::findOrFail($this->editingId)->update($this->only(['nama', 'tingkat', 'tipe', 'wali_kelas_id']));
        $this->resetForm();
        session()->flash('success', 'Kelas berhasil diperbarui!');
    }

    public function delete($id)
    {
        Kelas::findOrFail($id)->delete();
        session()->flash('success', 'Kelas berhasil dihapus!');
    }
}