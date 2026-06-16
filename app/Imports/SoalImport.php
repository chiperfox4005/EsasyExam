<?php

namespace App\Imports;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Auth;

class SoalImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        // Cari Mapel berdasarkan nama (case-insensitive)
        $mapel = MataPelajaran::whereRaw('LOWER(nama) = ?', [strtolower($row['mapel'])])->first();
        
        // Jika mapel tidak ditemukan, skip baris ini
        if (!$mapel) {
            return null; 
        }

        return new BankSoal([
            'guru_id' => Auth::id(),
            'mapel_id' => $mapel->id,
            'tipe' => strtolower($row['tipe']) === 'essay' ? 'essay' : 'pg',
            'pertanyaan' => $row['pertanyaan'],
            'opsi_a' => $row['opsi_a'] ?? null,
            'opsi_b' => $row['opsi_b'] ?? null,
            'opsi_c' => $row['opsi_c'] ?? null,
            'opsi_d' => $row['opsi_d'] ?? null,
            'opsi_e' => $row['opsi_e'] ?? null,
            'jawaban' => strtoupper($row['jawaban']),
            'level' => strtolower($row['level']),
            'status' => 'published',
        ]);
    }

    public function rules(): array
    {
        return [
            'mapel' => 'required|string',
            'tipe' => 'required|in:pg,essay',
            'pertanyaan' => 'required|string',
            'jawaban' => 'required|string',
            'level' => 'required|in:mudah,sedang,sulit',
        ];
    }
}