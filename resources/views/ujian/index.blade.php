@extends('layouts.app')

@section('title', 'Daftar Ujian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Ujian 📝</h1>
            <p class="text-gray-700 mt-1">Kelola semua ujian yang telah Anda buat</p>
        </div>
        <a href="{{ route('ujian.create') }}" class="px-5 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus mr-2"></i> Buat Ujian Baru
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
                    <i class="fas fa-clipboard-list text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujian->total() }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Total Ujian</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujian->filter(fn($u) => $u->status === 'published')->count() }}</p>
                    <p class="text-sm text-gray-600 font-semibold">Published</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $ujian->sum(fn($u) => $u->soalUjian->count()) }}
                    </p>
                    <p class="text-sm text-gray-600 font-semibold">Total Soal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- List Ujian -->
    <div class="bg-white rounded-3xl shadow-xl border-2 border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <tr>
                        <th class="p-4 text-left font-bold">Judul Ujian</th>
                        <th class="p-4 text-left font-bold">Mapel</th>
                        <th class="p-4 text-center font-bold">Mode</th>
                        <th class="p-4 text-center font-bold">Soal</th>
                        <th class="p-4 text-center font-bold">Durasi</th>
                        <th class="p-4 text-center font-bold">Status</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ujian as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-gray-900">{{ $u->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $u->created_at->format('d M Y') }}
                                </p>
                            </td>
                            <td class="p-4">
                                <span class="flex items-center gap-2 text-gray-700">
                                    <i class="fas {{ $u->mapel->icon ?? 'fa-book' }} text-blue-600"></i>
                                    {{ $u->mapel->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $u->mode === 'latihan' ? 'bg-green-100 text-green-700 border-2 border-green-300' : 'bg-blue-100 text-blue-700 border-2 border-blue-300' }}">
                                    {{ ucfirst($u->mode) }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-bold border-2 border-gray-300">
                                    {{ $u->soalUjian->count() }}
                                </span>
                            </td>
                            <td class="p-4 text-center text-sm text-gray-700 font-semibold">
                                @if($u->mode === 'ujian')
                                    {{ $u->durasi_menit }} mnt
                                @else
                                    <span class="text-green-600">∞</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $u->status == 'published' ? 'bg-green-100 text-green-700 border-2 border-green-300' : 'bg-gray-100 text-gray-700 border-2 border-gray-300' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('ujian.hasil', $u->id) }}" class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-bold shadow-md" title="Lihat Hasil">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                    <a href="{{ route('ujian.edit', $u->id) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-bold shadow-md" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('ujian.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus ujian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-bold shadow-md" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center">
                                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600 font-bold text-lg mb-2">Belum ada ujian yang dibuat</p>
                                <p class="text-gray-500 text-sm mb-4">Mulai buat ujian pertama Anda sekarang!</p>
                                <a href="{{ route('ujian.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-plus mr-2"></i> Buat Ujian Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($ujian->hasPages())
        <div class="mt-6">
            {{ $ujian->links() }}
        </div>
    @endif
</div>
@endsection