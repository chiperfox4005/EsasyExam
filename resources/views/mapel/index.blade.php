@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mata Pelajaran 📚</h1>
            <p class="text-gray-600 mt-1">Kelola mata pelajaran yang Anda ajarkan</p>
        </div>
        <a href="{{ route('mapel.create') }}" class="px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg shadow-blue-500/30 transition-all">
            <i class="fas fa-plus mr-2"></i> Tambah Mapel
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-2 border-green-300 text-green-800 px-4 py-3 rounded-2xl font-semibold">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-book text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $mapelList->total() }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Total Mapel</p>
                </div>
            </div>
        </div>
    </div>

    <!-- List Mapel -->
    <div class="bg-white rounded-3xl shadow-xl border-2 border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <tr>
                        <th class="p-4 text-left font-bold">Icon</th>
                        <th class="p-4 text-left font-bold">Nama Mapel</th>
                        <th class="p-4 text-left font-bold">Kode</th>
                        <th class="p-4 text-left font-bold">Guru</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($mapelList as $mapel)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                                    <i class="fas {{ $mapel->icon ?? 'fa-book' }} text-xl text-blue-600"></i>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-gray-900">{{ $mapel->nama }}</p>
                                @if($mapel->deskripsi)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($mapel->deskripsi, 50) }}</p>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-300">
                                    {{ $mapel->kode ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-700">
                                {{ $mapel->guru->name ?? 'Belum ditugaskan' }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('mapel.edit', $mapel->id) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-bold shadow-md">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('mapel.destroy', $mapel->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mapel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-bold shadow-md">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-book text-4xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-600 font-semibold mb-2">Belum ada mata pelajaran</p>
                                <p class="text-sm text-gray-500 mb-4">Mulai tambahkan mata pelajaran pertama Anda</p>
                                <a href="{{ route('mapel.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold">
                                    <i class="fas fa-plus mr-2"></i> Tambah Mapel
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($mapelList->hasPages())
        <div class="mt-6">
            {{ $mapelList->links() }}
        </div>
    @endif
</div>
@endsection