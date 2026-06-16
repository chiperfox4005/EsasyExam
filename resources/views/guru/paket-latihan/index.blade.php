@extends('layouts.app')

@section('title', 'Kelola Paket Latihan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📦 Kelola Paket Latihan</h1>
                <p class="text-purple-100">Buat dan kelola paket soal latihan untuk siswa</p>
            </div>
            <a href="{{ route('guru.paket-latihan.create') }}" 
               class="px-6 py-3 bg-white text-purple-600 rounded-xl font-bold hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Buat Paket Baru
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPaket }}</p>
                    <p class="text-sm text-gray-600">Total Paket</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSoal }}</p>
                    <p class="text-sm text-gray-600">Total Soal</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $mapelCount }}</p>
                    <p class="text-sm text-gray-600">Mapel</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-2 border-green-300 rounded-2xl p-4">
            <p class="text-green-800 font-semibold"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    <!-- List Paket -->
    @if($paketList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($paketList as $paket)
                <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden hover:shadow-xl transition-all">
                    <!-- Header -->
                    <div class="bg-gradient-to-r {{ $paket->is_active ? 'from-blue-500 to-indigo-600' : 'from-gray-400 to-gray-500' }} p-5 text-white">
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
                            <span class="px-2 py-1 {{ $paket->is_active ? 'bg-green-400' : 'bg-gray-400' }} rounded-full text-xs font-bold">
                                {{ $paket->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        @if($paket->deskripsi)
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($paket->deskripsi, 80) }}</p>
                        @endif

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600"><i class="fas fa-list-ol mr-2"></i>Total Soal</span>
                                <span class="font-bold text-gray-900">{{ $paket->total_soal }} soal</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600"><i class="fas fa-calendar mr-2"></i>Dibuat</span>
                                <span class="font-bold text-gray-900">{{ $paket->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('guru.paket-latihan.kelola-soal', $paket->id) }}" 
                               class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-bold text-center">
                                <i class="fas fa-puzzle-piece"></i> Kelola Soal
                            </a>
                            <a href="{{ route('guru.paket-latihan.edit', $paket->id) }}" 
                               class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm font-bold text-center">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                        <form action="{{ route('guru.paket-latihan.destroy', $paket->id) }}" method="POST" 
                              onsubmit="return confirm('Yakin hapus paket ini?')" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-bold">
                                <i class="fas fa-trash"></i> Hapus Paket
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $paketList->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center shadow-lg border-2 border-gray-100">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-4xl text-gray-400"></i>
            </div>
            <p class="text-gray-600 font-semibold">Belum ada paket latihan</p>
            <p class="text-sm text-gray-500 mt-1">Buat paket latihan pertama Anda</p>
            <a href="{{ route('guru.paket-latihan.create') }}" 
               class="mt-4 inline-block px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 font-bold">
                <i class="fas fa-plus mr-2"></i> Buat Paket Baru
            </a>
        </div>
    @endif

</div>
@endsection