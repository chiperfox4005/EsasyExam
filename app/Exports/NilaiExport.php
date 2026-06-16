<?php

namespace App\Exports;

use App\Models\HasilUjian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $ujianId;

    public function __construct($ujianId)
    {
        $this->ujianId = $ujianId;
    }

    public function collection()
    {
        return HasilUjian::with(['siswa', 'ujian.mapel'])
            ->where('ujian_id', $this->ujianId)
            ->where('status', 'graded')
            ->orderBy('nilai', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Nilai',
            'Benar',
            'Salah',
            'Kosong',
            'Status',
            'Tanggal Submit',
            'Waktu Pengerjaan',
        ];
    }

    public function map($hasil): array
    {
        static $no = 0;
        $no++;

        $waktuPengerjaan = '';
        if ($hasil->mulai_mengerjakan && $hasil->submitted_at) {
            $diff = $hasil->mulai_mengerjakan->diff($hasil->submitted_at);
            $waktuPengerjaan = sprintf(
                '%d jam %d menit %d detik',
                $diff->h,
                $diff->i,
                $diff->s
            );
        }

        return [
            $no,
            $hasil->siswa->name ?? '-',
            $hasil->siswa->nisn ?? '-',
            $hasil->siswa->kelas->nama ?? '-',
            number_format($hasil->nilai, 2),
            $hasil->benar ?? 0,
            $hasil->salah ?? 0,
            $hasil->kosong ?? 0,
            ucfirst($hasil->status),
            $hasil->submitted_at ? $hasil->submitted_at->format('d/m/Y H:i') : '-',
            $waktuPengerjaan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Rekap Nilai';
    }
}