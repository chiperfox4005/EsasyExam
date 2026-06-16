<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AiGenerateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HasilUjianController;

// ✅ IMPORT UNTUK LATIHAN & PAKET LATIHAN CONTROLLER
use App\Http\Controllers\Siswa\LatihanController;
use App\Http\Controllers\Guru\PaketLatihanController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Landing Page (Home)
Route::get('/', function () {
    return view('dashboard.landing');
})->name('home');

// Login Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// Logout (POST)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (Semua Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // ========== ADMIN ROUTES ==========
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        // Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
        
        // Guru
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{id}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{id}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');
        
        // Siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        
        // Admin bisa lihat semua hasil ujian
        Route::get('/admin/hasil-ujian', [HasilUjianController::class, 'adminIndex'])->name('admin.hasil-ujian.index');
    });
    
    // ========== GURU & ADMIN ROUTES ==========
    Route::middleware('role:guru,admin')->group(function () {
        // Dashboard Guru
        Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
        
        // Mata Pelajaran
        Route::get('/mapel', [MapelController::class, 'index'])->name('mapel.index');
        Route::get('/mapel/create', [MapelController::class, 'create'])->name('mapel.create');
        Route::post('/mapel', [MapelController::class, 'store'])->name('mapel.store');
        Route::get('/mapel/{id}/edit', [MapelController::class, 'edit'])->name('mapel.edit');
        Route::put('/mapel/{id}', [MapelController::class, 'update'])->name('mapel.update');
        Route::delete('/mapel/{id}', [MapelController::class, 'destroy'])->name('mapel.destroy');
        
        // Bank Soal
        Route::get('/bank-soal', [BankSoalController::class, 'index'])->name('bank-soal.index');
        Route::get('/bank-soal/create', [BankSoalController::class, 'create'])->name('bank-soal.create');
        Route::post('/bank-soal', [BankSoalController::class, 'store'])->name('bank-soal.store');
        Route::get('/bank-soal/{id}/edit', [BankSoalController::class, 'edit'])->name('bank-soal.edit');
        Route::put('/bank-soal/{id}', [BankSoalController::class, 'update'])->name('bank-soal.update');
        Route::delete('/bank-soal/{id}', [BankSoalController::class, 'destroy'])->name('bank-soal.destroy');
        Route::post('/bank-soal/import', [BankSoalController::class, 'import'])->name('bank-soal.import');
        Route::get('/bank-soal/template', [BankSoalController::class, 'downloadTemplate'])->name('bank-soal.template');
        
        // Ujian (Guru) - CRUD Lengkap
        Route::get('/ujian', [UjianController::class, 'index'])->name('ujian.index');
        Route::get('/ujian/create', [UjianController::class, 'create'])->name('ujian.create');
        Route::post('/ujian', [UjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/{id}/edit', [UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/ujian/{id}', [UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/ujian/{id}', [UjianController::class, 'destroy'])->name('ujian.destroy');
        
        // Hasil Ujian (Guru monitoring)
        Route::get('/ujian/{id}/hasil', [HasilUjianController::class, 'indexByUjian'])->name('ujian.hasil');
        Route::get('/ujian/{ujianId}/hasil/{siswaId}', [HasilUjianController::class, 'detail'])->name('ujian.hasil.detail');
        Route::get('/ujian/{id}/hasil/export', [HasilUjianController::class, 'export'])->name('ujian.hasil.export');
        
        // Rekap Nilai & Export
        Route::get('/ujian/{id}/rekap', [HasilUjianController::class, 'rekap'])->name('ujian.rekap');
        Route::get('/ujian/{id}/export', [HasilUjianController::class, 'exportExcel'])->name('ujian.export');
        
        // API untuk load soal (AJAX)
        Route::get('/api/soal/mapel/{mapelId}', [UjianController::class, 'getSoalByMapel'])->name('api.soal.mapel');
        
        // AI Generate
        Route::get('/ai-generate', [AiGenerateController::class, 'index'])->name('ai-generate.index');
        Route::post('/ai-generate', [AiGenerateController::class, 'generate'])->name('ai-generate.generate');
        Route::post('/ai-generate/save', [AiGenerateController::class, 'save'])->name('ai-generate.save');

        // Paket Latihan (Guru)
        Route::get('/paket-latihan', [PaketLatihanController::class, 'index'])->name('guru.paket-latihan.index');
        Route::get('/paket-latihan/create', [PaketLatihanController::class, 'create'])->name('guru.paket-latihan.create');
        Route::post('/paket-latihan', [PaketLatihanController::class, 'store'])->name('guru.paket-latihan.store');
        Route::get('/paket-latihan/{id}/edit', [PaketLatihanController::class, 'edit'])->name('guru.paket-latihan.edit');
        Route::put('/paket-latihan/{id}', [PaketLatihanController::class, 'update'])->name('guru.paket-latihan.update');
        Route::delete('/paket-latihan/{id}', [PaketLatihanController::class, 'destroy'])->name('guru.paket-latihan.destroy');
        Route::get('/paket-latihan/{id}/kelola-soal', [PaketLatihanController::class, 'kelolaSoal'])->name('guru.paket-latihan.kelola-soal');
        Route::post('/paket-latihan/{id}/tambah-soal', [PaketLatihanController::class, 'tambahSoal'])->name('guru.paket-latihan.tambah-soal');
        Route::delete('/paket-latihan/{id}/hapus-soal/{soalId}', [PaketLatihanController::class, 'hapusSoal'])->name('guru.paket-latihan.hapus-soal');
    });
    
    // ========== SISWA ROUTES ==========
    Route::middleware('role:siswa')->group(function () {
        Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
        
        // Ujian (Siswa)
        Route::get('/siswa/ujian', [UjianController::class, 'daftarUjian'])->name('siswa.ujian.daftar');
        Route::get('/siswa/ujian/{ujianId}/kerjakan', [UjianController::class, 'kerjakan'])->name('siswa.ujian.kerjakan');
        Route::post('/siswa/ujian/{ujianId}/simpan', [UjianController::class, 'simpanJawaban'])->name('siswa.ujian.simpan');
        Route::post('/siswa/ujian/{ujianId}/submit', [UjianController::class, 'submitUjian'])->name('siswa.ujian.submit');
        Route::get('/siswa/hasil/{ujianId}', [UjianController::class, 'hasil'])->name('siswa.ujian.hasil');
        
        // Riwayat Ujian Siswa
        Route::get('/siswa/riwayat', [UjianController::class, 'riwayat'])->name('siswa.riwayat');
        
        // Latihan Soal (Paket Latihan untuk Siswa)
        Route::get('/siswa/latihan', [LatihanController::class, 'index'])->name('siswa.latihan.index');
        Route::get('/siswa/latihan/{paketId}/kerjakan', [LatihanController::class, 'kerjakan'])->name('siswa.latihan.kerjakan');
        Route::post('/siswa/latihan/submit', [LatihanController::class, 'submit'])->name('siswa.latihan.submit');
        Route::get('/siswa/latihan/{paketId}/histori', [LatihanController::class, 'histori'])->name('siswa.latihan.histori'); // ✅ BARU
        
        // Menu Placeholder
        Route::get('/siswa/belajar', function () { return view('siswa.belajar'); })->name('siswa.belajar');
        Route::get('/siswa/ai-mentor', function () { return view('siswa.ai-mentor'); })->name('siswa.ai-mentor');
        Route::get('/siswa/badge', function () { return view('siswa.badge'); })->name('siswa.badge');
        Route::get('/siswa/leaderboard', function () { return view('siswa.leaderboard'); })->name('siswa.leaderboard');
    });

});