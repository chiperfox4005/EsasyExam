@extends('layouts.app')

@section('title', 'Hasil Ujian - ' . $ujian->judul)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hasil Ujian 📊</h1>
            <p class="text-gray-700 mt-1">{{ $ujian->judul }}</p>
        </div>
        <a href="{{ route('ujian.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $hasilList->total() }}</p>
                    <p class="text-sm text-gray-600">Total Peserta</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $hasilList->filter(fn($h) => $h->nilai >= 75)->count() }}
                    </p>
                    <p class="text-sm text-gray-600">Lulus</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $hasilList->filter(fn($h) => $h->nilai < 75)->count() }}
                    </p>
                    <p class="text-sm text-gray-600">Tidak Lulus</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-star text-2xl text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($hasilList->avg('nilai'), 1) }}
                    </p>
                    <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Hasil -->
    <div class="bg-white rounded-3xl shadow-xl border-2 border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <tr>
                        <th class="p-4 text-left font-bold">Siswa</th>
                        <th class="p-4 text-center font-bold">Nilai</th>
                        <th class="p-4 text-center font-bold">Benar</th>
                        <th class="p-4 text-center font-bold">Salah</th>
                        <th class="p-4 text-center font-bold">Status</th>
                        <th class="p-4 text-center font-bold">Waktu Pengerjaan</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($hasilList as $hasil)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-gray-900">{{ $hasil->siswa->name }}</p>
                                <p class="text-xs text-gray-500">{{ $hasil->siswa->nisn }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full font-bold text-sm {{ $hasil->nilai >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ number_format($hasil->nilai, 1) }}
                                </span>
                            </td>
                            <td class="p-4 text-center text-green-600 font-bold">{{ $hasil->benar }}</td>
                            <td class="p-4 text-center text-red-600 font-bold">{{ $hasil->salah }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $hasil->status === 'graded' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($hasil->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-center text-sm text-gray-600">
                                @if($hasil->submitted_at)
                                    {{ $hasil->submitted_at->diffForHumans($hasil->mulai_mengerjakan, true) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('ujian.hasil.detail', [$ujian->id, $hasil->siswa->id]) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-bold">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-600">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="font-semibold">Belum ada siswa yang mengerjakan ujian ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($hasilList->hasPages())
        <div class="mt-6">
            {{ $hasilList->links() }}
        </div>
    @endif
</div>
@endsection