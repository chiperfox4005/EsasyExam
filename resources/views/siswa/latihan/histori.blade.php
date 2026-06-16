@extends('layouts.app')

@section('title', 'Histori Pengerjaan - ' . $paket->judul)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📊 Histori Pengerjaan</h1>
                <p class="text-purple-100">{{ $paket->judul }}</p>
                <p class="text-purple-100 text-sm mt-1">{{ $paket->mapel->nama }}</p>
            </div>
            <a href="{{ route('siswa.latihan.kerjakan', $paket->id) }}" 
               class="px-6 py-3 bg-white text-purple-600 rounded-xl font-bold hover:shadow-lg transition-all">
                <i class="fas fa-play mr-2"></i> Latihan Lagi
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-redo text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPercobaan }}</p>
                    <p class="text-sm text-gray-600">Total Percobaan</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($rataRataNilai, 1) }}</p>
                    <p class="text-sm text-gray-600">Rata-rata Nilai</p>
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
                    <p class="text-sm text-gray-600">Nilai Tertinggi</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-2xl text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($nilaiTerendah, 1) }}</p>
                    <p class="text-sm text-gray-600">Nilai Terendah</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-4">
        <p class="text-blue-800 font-semibold">
            <i class="fas fa-info-circle mr-2"></i>
            Sistem akan otomatis reset histori setelah 10x percobaan untuk menghemat ruang penyimpanan.
        </p>
    </div>

    <!-- Tabel Histori -->
    <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list-ol text-blue-600"></i>
                Daftar Percobaan
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Percobaan Ke</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Nilai</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Benar</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Salah</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Kosong</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($historiList as $history)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">
                                    #{{ $history->percobaan_ke }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $history->nilai >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ number_format($history->nilai, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-green-600 font-semibold">{{ $history->benar }}</td>
                            <td class="px-6 py-4 text-center text-sm text-red-600 font-semibold">{{ $history->salah }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600 font-semibold">{{ $history->kosong }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">
                                {{ $history->selesai_at ? $history->selesai_at->format('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-600 font-semibold">Belum ada histori pengerjaan</p>
                                <p class="text-sm text-gray-500 mt-1">Mulai latihan untuk melihat histori</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex gap-3">
        <a href="{{ route('siswa.latihan.index') }}" 
           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-bold transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Paket
        </a>
    </div>

</div>
@endsection