<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ujian;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UjianManager extends Component
{
    use WithPagination;

    // Form Properties
    public $judul = '';
    public $mapel_id = '';
    public $kelas_id = '';
    public $durasi_menit = 60;
    public $mulai_at = '';
    public $selesai_at = '';
    public $tipe = 'quiz';
    public $mode = 'ujian';
    public $status = 'published';
    public $acak_soal = false;
    public $tampilkan_nilai = true;
    public $boleh_copy_paste = false;
    public $deteksi_tab_switch = true;
    public $max_tab_switch = 3;
    public $showForm = false;
    public $selectedSoal = [];

    public function render()
    {
        $ujian = Ujian::where('guru_id', Auth::id())
            ->with(['mapel', 'soalUjian'])
            ->latest()
            ->paginate(10);
            
        $mapelList = MataPelajaran::where('guru_id', Auth::id())->get();
        $kelasList = Kelas::all();
        
        $availableSoal = collect();
        if ($this->mapel_id) {
            $availableSoal = BankSoal::where('mapel_id', $this->mapel_id)
                ->where('status', 'published')
                ->where('guru_id', Auth::id())
                ->get();
        }

        return view('livewire.ujian-manager', [
            'ujian' => $ujian,
            'mapelList' => $mapelList,
            'kelasList' => $kelasList,
            'availableSoal' => $availableSoal
        ])->layout('layouts.app');
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function updatedMapelId()
    {
        $this->selectedSoal = []; 
    }

    public function updatedMode()
    {
        if ($this->mode === 'latihan') {
            $this->durasi_menit = 0;
            $this->boleh_copy_paste = true;
            $this->deteksi_tab_switch = false;
        } else {
            $this->durasi_menit = 60;
            $this->boleh_copy_paste = false;
            $this->deteksi_tab_switch = true;
        }
    }

    public function store()
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'mode' => 'required|in:latihan,ujian',
            'selectedSoal' => 'required|array|min:1',
        ];

        if ($this->mode === 'ujian') {
            $rules['durasi_menit'] = 'required|integer|min:1';
            $rules['mulai_at'] = 'required|date';
            $rules['selesai_at'] = 'required|date|after:mulai_at';
        }

        $this->validate($rules);

        $ujian = Ujian::create([
            'guru_id' => Auth::id(),
            'mapel_id' => $this->mapel_id,
            'kelas_id' => $this->kelas_id ?: null,
            'judul' => $this->judul,
            'durasi_menit' => $this->mode === 'ujian' ? $this->durasi_menit : 0,
            'mulai_at' => $this->mode === 'ujian' ? $this->mulai_at : now(),
            'selesai_at' => $this->mode === 'ujian' ? $this->selesai_at : now()->addYear(),
            'tipe' => $this->tipe,
            'mode' => $this->mode,
            'status' => 'published',
            'acak_soal' => $this->acak_soal,
            'tampilkan_nilai' => $this->tampilkan_nilai,
            'boleh_copy_paste' => $this->boleh_copy_paste,
            'deteksi_tab_switch' => $this->deteksi_tab_switch,
            'max_tab_switch' => $this->max_tab_switch,
        ]);

        foreach ($this->selectedSoal as $index => $soalId) {
            $ujian->soalUjian()->create([
                'bank_soal_id' => $soalId,
                'urutan' => $index + 1
            ]);
        }

        session()->flash('success', ucfirst($this->mode) . ' berhasil dibuat!');
        $this->closeForm();
    }

    public function delete($id)
    {
        Ujian::where('guru_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Ujian berhasil dihapus!');
    }

    private function resetForm()
    {
        $this->judul = '';
        $this->mapel_id = '';
        $this->kelas_id = '';
        $this->durasi_menit = 60;
        $this->mulai_at = '';
        $this->selesai_at = '';
        $this->tipe = 'quiz';
        $this->mode = 'ujian';
        $this->acak_soal = false;
        $this->tampilkan_nilai = true;
        $this->boleh_copy_paste = false;
        $this->deteksi_tab_switch = true;
        $this->max_tab_switch = 3;
        $this->selectedSoal = [];
    }
}