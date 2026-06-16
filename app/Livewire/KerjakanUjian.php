<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\SoalUjian;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KerjakanUjian extends Component
{
    public $ujianId;
    public $ujian;
    public $soalList;
    public $currentIndex = 0;
    public $jawaban = [];
    public $waktuSisa = 0;
    public $isFinished = false;
    public $hasil;
    public $logPelanggaran = [];
    public $jumlahPelanggaran = 0;

    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
        $this->ujian = Ujian::findOrFail($ujianId);
        
        $this->hasil = HasilUjian::where('ujian_id', $ujianId)->where('siswa_id', Auth::id())->first();
        
        if ($this->hasil && $this->hasil->status === 'submitted') {
            $this->isFinished = true;
            return;
        }

        $query = SoalUjian::where('ujian_id', $ujianId)->with('soal');
        if ($this->ujian->acak_soal) {
            $query->inRandomOrder();
        } else {
            $query->orderBy('urutan');
        }
        $this->soalList = $query->get();

        if ($this->hasil) {
            $this->jawaban = $this->hasil->jawaban ?? [];
            $this->logPelanggaran = $this->hasil->log_pelanggaran ?? [];
            $this->jumlahPelanggaran = $this->hasil->jumlah_pelanggaran ?? 0;
            
            if ($this->ujian->mode === 'ujian') {
                $endTime = Carbon::parse($this->hasil->mulai_mengerjakan)->addMinutes($this->ujian->durasi_menit);
                $this->waktuSisa = max(0, now()->diffInSeconds($endTime));
            }
        } else {
            $this->hasil = HasilUjian::create([
                'ujian_id' => $ujianId,
                'siswa_id' => Auth::id(),
                'mulai_mengerjakan' => now(),
                'status' => 'ongoing'
            ]);
            
            if ($this->ujian->mode === 'ujian') {
                $this->waktuSisa = $this->ujian->durasi_menit * 60;
            }
        }
    }

    public function saveJawaban($soalId, $jawaban)
    {
        $this->jawaban[$soalId] = $jawaban;
        HasilUjian::where('ujian_id', $this->ujianId)
            ->where('siswa_id', Auth::id())
            ->update(['jawaban' => $this->jawaban]);
    }

    public function catatPelanggaran($tipe, $detail = '')
    {
        $this->logPelanggaran[] = [
            'tipe' => $tipe,
            'detail' => $detail,
            'waktu' => now()->format('H:i:s')
        ];
        $this->jumlahPelanggaran++;
        
        HasilUjian::where('ujian_id', $this->ujianId)
            ->where('siswa_id', Auth::id())
            ->update([
                'log_pelanggaran' => $this->logPelanggaran,
                'jumlah_pelanggaran' => $this->jumlahPelanggaran
            ]);
    }

    public function submitUjian()
    {
        $this->hasil->update([
            'submitted_at' => now(),
            'status' => 'submitted'
        ]);
        $this->gradeUjian();
        $this->isFinished = true;
    }

    private function gradeUjian()
    {
        $benar = 0; $salah = 0; $kosong = 0;
        $totalSoalPG = 0;

        foreach ($this->soalList as $item) {
            $soal = $item->soal;
            if ($soal->tipe !== 'pg') continue;
            
            $totalSoalPG++;
            $jawabanSiswa = $this->jawaban[$soal->id] ?? null;
            
            if (!$jawabanSiswa) {
                $kosong++;
            } elseif ($jawabanSiswa === $soal->jawaban) {
                $benar++;
            } else {
                $salah++;
            }
        }

        $nilai = $totalSoalPG > 0 ? ($benar / $totalSoalPG) * 100 : 0;

        $this->hasil->update([
            'benar' => $benar,
            'salah' => $salah,
            'kosong' => $kosong,
            'nilai' => $nilai,
            'status' => 'graded'
        ]);
    }

    public function render()
    {
        return view('livewire.kerjakan-ujian')
            ->layout('layouts.app');
    }
}