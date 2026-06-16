@extends('layouts.app')

@section('title', 'Latihan Soal')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-xl">
        <h1 class="text-3xl font-bold mb-2">📚 Paket Latihan</h1>
        <p class="text-emerald-100">Pilih paket latihan untuk memulai</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
        <form method="GET" action="{{ route('siswa.latihan.index') }}" class="flex gap-3">
            <div class="flex-1">
                <select name="mapel_id" class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" {{ $mapelId == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama }} ({{ $mapel->paket_latihan_count }} paket)
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('siswa.latihan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold">
                <i class="fas fa-redo"></i>
            </a>
        </form>
    </div>

    <!-- List Paket Latihan -->
    @if($paketList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($paketList as $paket)
                <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden hover:shadow-xl transition-all">
                    <!-- Header Paket -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-5 text-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <i class="fas {{ $paket->mapel->icon ?? 'fa-book' }} text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">{{ $paket->judul }}</h3>
                                    <p class="text-blue-100 text-sm">{{ $paket->mapel->nama }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body Paket -->
                    <div class="p-5">
                        @if($paket->deskripsi)
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($paket->deskripsi, 100) }}</p>
                        @endif

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-list-ol mr-2"></i>Total Soal
                                </span>
                                <span class="font-bold text-gray-900">{{ $paket->total_soal }} soal</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-user mr-2"></i>Dibuat oleh
                                </span>
                                <span class="font-bold text-gray-900">{{ $paket->guru->name }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-calendar mr-2"></i>Dibuat
                                </span>
                                <span class="font-bold text-gray-900">{{ $paket->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('siswa.latihan.kerjakan', $paket->id) }}" 
                           class="block w-full text-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-bold shadow-lg transition-all">
                            <i class="fas fa-play mr-2"></i> Mulai Latihan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $paketList->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center shadow-lg border-2 border-gray-100">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400"></i>
            </div>
            <p class="text-gray-600 font-semibold">Belum ada paket latihan tersedia</p>
            <p class="text-sm text-gray-500 mt-1">Tunggu guru membuat paket latihan untuk mapel Anda</p>
        </div>
    @endif

</div>
@endsection