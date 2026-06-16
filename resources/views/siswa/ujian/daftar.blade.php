@extends('layouts.app')

@section('title', 'Daftar Ujian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">📝 Daftar Ujian</h1>
            <p class="text-blue-100 text-lg">Pilih ujian yang ingin kamu kerjakan</p>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white bg-opacity-10 rounded-full translate-y-24 -translate-x-24"></div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clipboard-list text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujianList->count() }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Ujian Tersedia</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujianList->filter(fn($u) => $u->sudah_dikerjakan)->count() }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Sudah Dikerjakan</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-hourglass-half text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujianList->filter(fn($u) => !$u->sudah_dikerjakan)->count() }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Belum Dikerjakan</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-star text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($ujianList->avg('rata_rata_nilai') ?? 0, 1) }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Rata-rata Nilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-3xl p-4 shadow-lg border-2 border-gray-200">
        <div class="flex gap-2 flex-wrap">
            <button onclick="filterUjian('semua')" class="filter-btn px-4 py-2 rounded-xl font-semibold text-sm bg-blue-600 text-white">
                <i class="fas fa-list mr-1"></i> Semua
            </button>
            <button onclick="filterUjian('belum')" class="filter-btn px-4 py-2 rounded-xl font-semibold text-sm bg-gray-100 text-gray-700 hover:bg-gray-200">
                <i class="fas fa-clock mr-1"></i> Belum Dikerjakan
            </button>
            <button onclick="filterUjian('sudah')" class="filter-btn px-4 py-2 rounded-xl font-semibold text-sm bg-gray-100 text-gray-700 hover:bg-gray-200">
                <i class="fas fa-check mr-1"></i> Sudah Dikerjakan
            </button>
        </div>
    </div>

    <!-- List Ujian -->
    <div class="space-y-4" id="ujianContainer">
        @forelse($ujianList as $ujian)
            <div class="ujian-item bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-200 hover:shadow-xl transition-all"
                 data-status="{{ $ujian->sudah_dikerjakan ? 'sudah' : 'belum' }}">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Info Ujian -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3 flex-wrap">
                            <!-- Badge Mode -->
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $ujian->mode === 'ujian' ? 'bg-blue-100 text-blue-700 border-2 border-blue-300' : 'bg-green-100 text-green-700 border-2 border-green-300' }}">
                                {{ $ujian->mode === 'ujian' ? '📝 UJIAN' : '📖 LATIHAN' }}
                            </span>
                            
                            <!-- Badge Tipe -->
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700 border-2 border-purple-300">
                                {{ strtoupper($ujian->tipe) }}
                            </span>
                            
                            <!-- Status -->
                            @if($ujian->sudah_dikerjakan)
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border-2 border-green-300">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 border-2 border-yellow-300">
                                    <i class="fas fa-clock mr-1"></i> Belum Dikerjakan
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $ujian->judul }}</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas {{ $ujian->mapel->icon ?? 'fa-book' }} text-blue-600"></i>
                                <span class="font-semibold">{{ $ujian->mapel->nama ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-list-ol text-purple-600"></i>
                                <span class="font-semibold">{{ $ujian->soalUjian->count() }} Soal</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-clock text-orange-600"></i>
                                <span class="font-semibold">{{ $ujian->durasi_menit }} menit</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-calendar text-red-600"></i>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($ujian->selesai_at)->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                        
                        @if($ujian->sudah_dikerjakan && $ujian->rata_rata_nilai !== null)
                            <div class="mt-3 p-3 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-trophy text-yellow-500 text-xl"></i>
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold">Nilai Kamu:</p>
                                        <p class="text-lg font-bold {{ $ujian->rata_rata_nilai >= 75 ? 'text-green-700' : 'text-red-700' }}">
                                            {{ number_format($ujian->rata_rata_nilai, 1) }}
                                        </p>
                                    </div>
                                    <a href="{{ route('siswa.ujian.hasil', $ujian->id) }}" class="ml-auto px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-bold">
                                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Button -->
                    <div class="flex-shrink-0">
                        @if($ujian->sudah_dikerjakan)
                            <a href="{{ route('siswa.ujian.hasil', $ujian->id) }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 font-bold shadow-lg shadow-green-500/30 transition-all">
                                <i class="fas fa-eye"></i>
                                <span>Lihat Hasil</span>
                            </a>
                        @else
                            <a href="{{ route('siswa.ujian.kerjakan', $ujian->id) }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">
                                <i class="fas fa-play"></i>
                                <span>Kerjakan</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 shadow-lg border-2 border-gray-200 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Ujian</h3>
                <p class="text-gray-600">Tidak ada ujian yang tersedia saat ini. Tunggu pengumuman dari guru ya!</p>
            </div>
        @endforelse
    </div>
</div>

<script>
function filterUjian(status) {
    // Update button styles
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    event.target.classList.add('bg-blue-600', 'text-white');
    
    // Filter items
    const items = document.querySelectorAll('.ujian-item');
    items.forEach(item => {
        if (status === 'semua') {
            item.style.display = 'block';
        } else {
            item.style.display = item.dataset.status === status ? 'block' : 'none';
        }
    });
}
</script>
@endsection