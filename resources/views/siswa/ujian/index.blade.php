@extends('layouts.app')

@section('title', 'Daftar Ujian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <h1 class="text-3xl font-bold mb-2">📝 Daftar Ujian</h1>
        <p class="text-blue-100">Ujian yang tersedia untuk Anda</p>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-4">
        <p class="text-blue-800 font-semibold">
            <i class="fas fa-info-circle mr-2"></i>
            Menampilkan {{ $ujianList->count() }} ujian yang tersedia
        </p>
    </div>

    <!-- List Ujian -->
    @if($ujianList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ujianList as $ujian)
                <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-200 overflow-hidden hover:shadow-xl transition-all">
                    
                    <!-- Header Card -->
                    <div class="p-6 bg-gradient-to-br {{ $ujian->mode === 'ujian' ? 'from-blue-500 to-indigo-600' : 'from-green-500 to-emerald-600' }} text-white">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-bold">
                                {{ $ujian->mode === 'ujian' ? '📝 UJIAN' : '📖 LATIHAN' }}
                            </span>
                            @if($ujian->sudah_dikerjakan)
                                <span class="px-3 py-1 bg-green-400 rounded-full text-xs font-bold">
                                    ✅ Selesai
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ $ujian->judul }}</h3>
                        <div class="flex items-center gap-2 text-sm text-blue-100">
                            <i class="fas {{ $ujian->mapel->icon ?? 'fa-book' }}"></i>
                            <span>{{ $ujian->mapel->nama ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6">
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-list-ol text-purple-500"></i>
                                    Jumlah Soal
                                </span>
                                <span class="font-bold text-gray-900">{{ $ujian->soalUjian->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-clock text-orange-500"></i>
                                    Durasi
                                </span>
                                <span class="font-bold text-gray-900">
                                    @if($ujian->mode === 'ujian')
                                        {{ $ujian->durasi_menit }} menit
                                    @else
                                        <span class="text-green-600">∞ Tanpa batas</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-calendar text-red-500"></i>
                                    Berakhir
                                </span>
                                <span class="font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($ujian->selesai_at)->format('d M Y') }}
                                </span>
                            </div>
                            @if($ujian->sudah_dikerjakan)
                                <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-star text-yellow-500"></i>
                                        Nilai Anda
                                    </span>
                                    <span class="font-bold text-lg {{ $ujian->rata_rata_nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($ujian->rata_rata_nilai, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        @if($ujian->sudah_dikerjakan)
                            <a href="{{ route('siswa.ujian.hasil', $ujian->id) }}" 
                               class="block w-full text-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                                <i class="fas fa-eye mr-2"></i> Lihat Hasil
                            </a>
                        @else
                            <a href="{{ route('siswa.ujian.start', $ujian->id) }}" 
                               class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:shadow-lg hover:scale-105 transition-all">
                                <i class="fas fa-play mr-2"></i> Kerjakan Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-200 p-12 text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-5xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Ujian Tersedia</h3>
            <p class="text-gray-600 mb-6">Tunggu pengumuman dari guru untuk ujian berikutnya</p>
            <a href="{{ route('siswa.dashboard') }}" 
               class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>
@endsection