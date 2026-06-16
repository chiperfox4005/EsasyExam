<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;

class BankSoalManager extends Component
{
    use WithPagination, WithFileUploads;

    // Form Properties
    public $tipe = 'pg', $pertanyaan = '', $mapel_id = '';
    public $opsi_a, $opsi_b, $opsi_c, $opsi_d, $opsi_e, $jawaban = '';
    public $level = 'sedang', $status = 'draft';
    public $gambar_soal, $editingId;

    // Filter Properties
    public $search = '', $filterMapel = '', $filterLevel = '';

    protected $rules = [
        'pertanyaan' => 'required|string',
        'mapel_id' => 'required|exists:mata_pelajaran,id',
        'tipe' => 'required|in:pg,essay,gambar',
        'level' => 'required|in:mudah,sedang,sulit',
        'jawaban' => 'required|string',
        'gambar_soal' => 'nullable|image|max:2048', // Max 2MB
    ];

    public function render()
    {
        $mapelList = MataPelajaran::all();
        
        $soal = BankSoal::where('guru_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('pertanyaan', 'like', "%{$this->search}%"))
            ->when($this->filterMapel, fn($q) => $q->where('mapel_id', $this->filterMapel))
            ->when($this->filterLevel, fn($q) => $q->where('level', $this->filterLevel))
            ->with('mapel')
            ->latest()
            ->paginate(10);

        return view('livewire.bank-soal-manager', compact('soal', 'mapelList'))
            ->layout('layouts.app');
    }

    public function resetForm()
    {
        $this->reset(['pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban', 'gambar_soal', 'editingId']);
        $this->tipe = 'pg';
        $this->level = 'sedang';
        $this->status = 'draft';
    }

    public function store()
    {
        $this->validate();

        $data = $this->only(['guru_id', 'mapel_id', 'tipe', 'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban', 'level', 'status']);
        $data['guru_id'] = Auth::id();

        if ($this->gambar_soal) {
            $data['gambar_soal'] = $this->gambar_soal->store('soal-images');
        }

        BankSoal::create($data);
        $this->resetForm();
        session()->flash('success', 'Soal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $soal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);
        $this->editingId = $id;
        $this->mapel_id = $soal->mapel_id;
        $this->tipe = $soal->tipe;
        $this->pertanyaan = $soal->pertanyaan;
        $this->opsi_a = $soal->opsi_a;
        $this->opsi_b = $soal->opsi_b;
        $this->opsi_c = $soal->opsi_c;
        $this->opsi_d = $soal->opsi_d;
        $this->opsi_e = $soal->opsi_e;
        $this->jawaban = $soal->jawaban;
        $this->level = $soal->level;
        $this->status = $soal->status;
    }

    public function update()
    {
        $this->validate();
        
        $soal = BankSoal::where('guru_id', Auth::id())->findOrFail($this->editingId);
        $data = $this->only(['mapel_id', 'tipe', 'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban', 'level', 'status']);

        if ($this->gambar_soal) {
            $data['gambar_soal'] = $this->gambar_soal->store('soal-images');
        }

        $soal->update($data);
        $this->resetForm();
        session()->flash('success', 'Soal berhasil diperbarui!');
    }

    public function delete($id)
    {
        BankSoal::where('guru_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Soal berhasil dihapus!');
    }
}