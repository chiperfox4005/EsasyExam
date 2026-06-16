@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Bank Soal 📚</h1>
            <p class="text-gray-600 mt-1">Kelola semua soal yang telah Anda buat</p>
        </div>
        <a href="{{ route('bank-soal.create') }}" class="px-5 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus mr-2"></i> Tambah Soal
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-2 border-green-300 text-green-800 px-4 py-3 rounded-2xl font-semibold">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-database text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSoal ?? 0 }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Total Soal</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-list-ol text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPG ?? 0 }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Pilihan Ganda</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-pen text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalEssay ?? 0 }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Essay</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
        <form method="GET" action="{{ route('bank-soal.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🔍 Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari pertanyaan..."
                       class="w-full px-4 py-2.5 bg-gray-50 border-2 border-gray-200 rounded-xl">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">📖 Mapel</label>
                <select name="mapel_id" class="w-full px-4 py-2.5 bg-gray-50 border-2 border-gray-200 rounded-xl">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList ?? [] as $m)
                        <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">📝 Tipe</label>
                <select name="tipe" class="w-full px-4 py-2.5 bg-gray-50 border-2 border-gray-200 rounded-xl">
                    <option value="">Semua Tipe</option>
                    <option value="pg" {{ request('tipe') == 'pg' ? 'selected' : '' }}>Pilihan Ganda</option>
                    <option value="essay" {{ request('tipe') == 'essay' ? 'selected' : '' }}>Essay</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">🎯 Level</label>
                <select name="level" class="w-full px-4 py-2.5 bg-gray-50 border-2 border-gray-200 rounded-xl">
                    <option value="">Semua Level</option>
                    <option value="mudah" {{ request('level') == 'mudah' ? 'selected' : '' }}>Mudah</option>
                    <option value="sedang" {{ request('level') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="sulit" {{ request('level') == 'sulit' ? 'selected' : '' }}>Sulit</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('bank-soal.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-bold">
                    <i class="fas fa-undo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- List Soal -->
    <div class="bg-white rounded-3xl shadow-xl border-2 border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <tr>
                        <th class="p-4 text-left font-bold">Pertanyaan</th>
                        <th class="p-4 text-left font-bold">Mapel</th>
                        <th class="p-4 text-center font-bold">Tipe</th>
                        <th class="p-4 text-center font-bold">Level</th>
                        <th class="p-4 text-center font-bold">Jawaban</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($soalList as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-gray-900">{{ Str::limit($item->pertanyaan, 80) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </p>
                            </td>
                            <td class="p-4">
                                <span class="flex items-center gap-2 text-gray-700">
                                    <i class="fas {{ $item->mapel->icon ?? 'fa-book' }} text-blue-600"></i>
                                    {{ $item->mapel->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $item->tipe === 'pg' ? 'bg-blue-100 text-blue-700 border-2 border-blue-300' : 'bg-purple-100 text-purple-700 border-2 border-purple-300' }}">
                                    {{ $item->tipe === 'pg' ? 'PILIHAN GANDA' : 'ESSAY' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    {{ $item->level === 'mudah' ? 'bg-green-100 text-green-700 border-2 border-green-300' : 
                                       ($item->level === 'sulit' ? 'bg-red-100 text-red-700 border-2 border-red-300' : 
                                       'bg-yellow-100 text-yellow-700 border-2 border-yellow-300') }}">
                                    {{ ucfirst($item->level) }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                @if($item->tipe === 'pg')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold text-sm">
                                        {{ $item->jawaban }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">
                                        {{ Str::limit($item->jawaban, 30) }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('bank-soal.edit', $item->id) }}" 
                                       class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-bold shadow-md" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('bank-soal.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Yakin hapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-bold shadow-md" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600 font-bold text-lg mb-2">Belum ada soal</p>
                                <p class="text-gray-500 text-sm mb-4">Mulai buat soal pertama Anda</p>
                                <a href="{{ route('bank-soal.create') }}" 
                                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-plus mr-2"></i> Tambah Soal
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($soalList->hasPages())
        <div class="mt-6">
            {{ $soalList->links() }}
        </div>
    @endif
</div>
@endsection