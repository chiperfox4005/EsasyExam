<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use App\Models\BankSoal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Guru (gunakan updateOrCreate)
        $guru1 = User::updateOrCreate(
            ['email' => 'guru@esasyexam.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'nip' => '123456789',
                'phone' => '081234567890',
            ]
        );

        $guru2 = User::updateOrCreate(
            ['email' => 'guru2@esasyexam.com'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'nip' => '987654321',
                'phone' => '081234567891',
            ]
        );

        // 2. Buat Kelas
        $kelas1 = Kelas::updateOrCreate(
            ['nama' => 'X IPA 1'],
            [
                'tingkat' => 10,
                'tipe' => 'formal',
                'wali_kelas_id' => $guru1->id,
            ]
        );

        $kelas2 = Kelas::updateOrCreate(
            ['nama' => 'XI IPA 1'],
            [
                'tingkat' => 11,
                'tipe' => 'formal',
                'wali_kelas_id' => $guru1->id,
            ]
        );

        $kelas3 = Kelas::updateOrCreate(
            ['nama' => 'XII IPA 1'],
            [
                'tingkat' => 12,
                'tipe' => 'formal',
                'wali_kelas_id' => $guru2->id,
            ]
        );

        // 3. Buat Mata Pelajaran
        $mapel1 = MataPelajaran::updateOrCreate(
            ['kode' => 'MTK-10'],
            [
                'nama' => 'Matematika',
                'deskripsi' => 'Matematika untuk kelas X',
                'icon' => 'fa-calculator',
                'kelas_id' => $kelas1->id,
                'guru_id' => $guru1->id,
            ]
        );

        $mapel2 = MataPelajaran::updateOrCreate(
            ['kode' => 'FIS-11'],
            [
                'nama' => 'Fisika',
                'deskripsi' => 'Fisika untuk kelas XI',
                'icon' => 'fa-atom',
                'kelas_id' => $kelas2->id,
                'guru_id' => $guru1->id,
            ]
        );

        $mapel3 = MataPelajaran::updateOrCreate(
            ['kode' => 'KIM-12'],
            [
                'nama' => 'Kimia',
                'deskripsi' => 'Kimia untuk kelas XII',
                'icon' => 'fa-flask',
                'kelas_id' => $kelas3->id,
                'guru_id' => $guru2->id,
            ]
        );

        MataPelajaran::updateOrCreate(
            ['kode' => 'BIND-10'],
            [
                'nama' => 'Bahasa Indonesia',
                'deskripsi' => 'Bahasa Indonesia untuk kelas X',
                'icon' => 'fa-book',
                'kelas_id' => null,
                'guru_id' => $guru1->id,
            ]
        );

        MataPelajaran::updateOrCreate(
            ['kode' => 'BING-11'],
            [
                'nama' => 'Bahasa Inggris',
                'deskripsi' => 'Bahasa Inggris untuk kelas XI',
                'icon' => 'fa-language',
                'kelas_id' => null,
                'guru_id' => $guru2->id,
            ]
        );

        // 4. Buat Siswa
        User::updateOrCreate(
            ['nisn' => '0012345678'],
            [
                'name' => 'Ahmad Rizki',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas_id' => $kelas3->id,
                'phone' => '081234567892',
            ]
        );

        User::updateOrCreate(
            ['nisn' => '0012345679'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas_id' => $kelas3->id,
                'phone' => '081234567893',
            ]
        );

        // 5. Buat Bank Soal
        BankSoal::updateOrCreate(
            [
                'guru_id' => $guru1->id,
                'pertanyaan' => 'Berapa hasil dari 5 + 3?',
            ],
            [
                'mapel_id' => $mapel1->id,
                'tipe' => 'pg',
                'opsi_a' => '6',
                'opsi_b' => '7',
                'opsi_c' => '8',
                'opsi_d' => '9',
                'opsi_e' => '10',
                'jawaban' => 'C',
                'level' => 'mudah',
                'status' => 'published',
            ]
        );

        BankSoal::updateOrCreate(
            [
                'guru_id' => $guru1->id,
                'pertanyaan' => 'Berapa hasil dari 10 x 5?',
            ],
            [
                'mapel_id' => $mapel1->id,
                'tipe' => 'pg',
                'opsi_a' => '40',
                'opsi_b' => '45',
                'opsi_c' => '50',
                'opsi_d' => '55',
                'opsi_e' => '60',
                'jawaban' => 'C',
                'level' => 'mudah',
                'status' => 'published',
            ]
        );

        BankSoal::updateOrCreate(
            [
                'guru_id' => $guru1->id,
                'pertanyaan' => 'Apa satuan kecepatan dalam SI?',
            ],
            [
                'mapel_id' => $mapel2->id,
                'tipe' => 'pg',
                'opsi_a' => 'Meter',
                'opsi_b' => 'Meter per detik',
                'opsi_c' => 'Kilometer',
                'opsi_d' => 'Detik',
                'opsi_e' => 'Newton',
                'jawaban' => 'B',
                'level' => 'mudah',
                'status' => 'published',
            ]
        );
    }
}