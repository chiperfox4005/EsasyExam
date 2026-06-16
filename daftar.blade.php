@extends('layouts.app')

@section('title', 'Daftar Ujian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Daftar Ujian 📝</h1>
        <p class="text-gray-600 mt-1">Pilih ujian yang ingin kamu kerjakan</p>
    </div>

    <!-- Daftar Ujian -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ujian as $u)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <i class="fas {{ $u->mapel->icon ?? 'fa-book' }} text-2xl text-blue-600"></i>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full {{ $u->mode === 'latihan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($u->mode) }}
                    </span>
                </div>
                
                <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $u->judul }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ $u->mapel->nama }}</p>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <div class="flex items-center gap-2">
                        <i class="far fa-clock text-gray-400"></i>
                        <span>{{ $u->durasi_menit }} menit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-list text-gray-400"></i>
                        <span>{{ $u->soalUjian->count() }} soal</span>
                    </div>
                </div>

                @if($u->status_pengerjaan === 'belum')
                    <a href="{{ route('siswa.ujian.kerjakan', $u->id) }}" class="block w-full px-4 py-3 bg-blue-600 text-white text-center rounded-2xl hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-play mr-2"></i> Mulai Mengerjakan
                    </a>
                @elseif($u->status_pengerjaan === 'ongoing')
                    <a href="{{ route('siswa.ujian.kerjakan', $u->id) }}" class="block w-full px-4 py-3 bg-yellow-500 text-white text-center rounded-2xl hover:bg-yellow-600 transition-colors font-semibold">
                        <i class="fas fa-edit mr-2"></i> Lanjutkan
                    </a>
                @elseif($u->status_pengerjaan === 'submitted' || $u->status_pengerjaan === 'graded')
                    <a href="{{ route('siswa.ujian.hasil', $u->id) }}" class="block w-full px-4 py-3 bg-green-600 text-white text-center rounded-2xl hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Lihat Hasil
                    </a>
                    @if($u->nilai !== null)
                        <div class="mt-3 text-center">
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($u->nilai, 1) }}</span>
                            <span class="text-sm text-gray-500">/ 100</span>
                        </div>
                    @endif
                @endif
            </div>
        @empty
            <div class="col-span-3 text-center py-10">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada ujian yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection