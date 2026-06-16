@extends('layouts.app')

@section('title', 'Rekap Nilai - ' . $ujian->judul)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📊 Rekap Nilai</h1>
                <p class="text-purple-100">{{ $ujian->judul }}</p>
                <p class="text-purple-100 text-sm mt-1">{{ $ujian->mapel->nama ?? '-' }} • {{ $ujian->tipe }} • {{ $ujian->mode }}</p>
            </div>
            <a href="{{ route('ujian.export', $ujian->id) }}" 
               class="px-6 py-3 bg-white text-purple-600 rounded-xl font-bold hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                    <p class="text-sm text-gray-600">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($rataRata, 1) }}</p>
                    <p class="text-sm text-gray-600">Rata-rata</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trophy text-2xl text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($nilaiTertinggi, 1) }}</p>
                    <p class="text-sm text-gray-600">Tertinggi</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-green-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $lulus }}</p>
                    <p class="text-sm text-gray-600">Lulus (≥75)</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-red-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $tidakLulus }}</p>
                    <p class="text-sm text-gray-600">Tidak Lulus</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Nilai -->
    <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list-ol text-blue-600"></i>
                Daftar Nilai Siswa
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Kelas</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Nilai</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Benar</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Salah</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($hasilList as $index => $hasil)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $hasil->siswa->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($hasil->siswa->name) }}" 
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $hasil->siswa->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $hasil->siswa->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $hasil->siswa->kelas->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $hasil->nilai >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ number_format($hasil->nilai, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-green-600 font-semibold">{{ $hasil->benar ?? 0 }}</td>
                            <td class="px-6 py-4 text-center text-sm text-red-600 font-semibold">{{ $hasil->salah ?? 0 }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $hasil->status === 'graded' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($hasil->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('ujian.hasil.detail', [$ujian->id, $hasil->siswa_id]) }}" 
                                   class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-bold">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-600 font-semibold">Belum ada siswa yang mengerjakan</p>
                                <p class="text-sm text-gray-500 mt-1">Bagikan link ujian ke siswa agar mereka bisa mengerjakan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex gap-3">
        <a href="{{ route('ujian.index') }}" 
           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-bold transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Ujian
        </a>
    </div>

</div>
@endsection