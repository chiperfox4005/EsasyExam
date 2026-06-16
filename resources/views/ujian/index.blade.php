@extends('layouts.app')

@section('title', 'Daftar Ujian')

@push('styles')
<style>
    /* Container lebih sempit & center */
    .max-w-7xl {
        max-width: 1200px !important; /* Dari 1280px jadi 1200px */
        margin-left: auto !important;
        margin-right: auto !important;
    }
    
    /* Padding lebih compact */
    .space-y-6 {
        row-gap: 1.5rem !important;
    }
    
    /* Stats card lebih compact */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Tabel lebih compact */
    table th {
        padding: 0.875rem 1rem !important;
        font-size: 0.95rem !important;
    }
    
    table td {
        padding: 1rem !important;
        font-size: 0.9rem !important;
    }
    
    /* Judul lebih compact */
    .text-3xl {
        font-size: 1.875rem !important;
    }
    
    /* Tombol lebih compact */
    .btn-compact {
        padding: 0.5rem 1rem !important;
        font-size: 0.875rem !important;
    }
    
    /* Badge lebih kecil */
    .badge-compact {
        padding: 0.375rem 0.75rem !important;
        font-size: 0.8rem !important;
    }
    
    /* Icon action lebih kecil */
    .action-btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }
    
    /* Stats card padding */
    .stats-card {
        padding: 1.25rem !important;
    }
    
    /* Table container */
    .table-container {
        border-radius: 1rem;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Ujian 📝</h1>
            <p class="text-gray-600 mt-1">Kelola semua ujian yang telah Anda buat</p>
        </div>
        <a href="{{ route('ujian.create') }}" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold shadow-md">
            <i class="fas fa-plus mr-2"></i> Buat Ujian Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="stats-grid">
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-200 stats-card">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-clipboard-list text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujian->total() }}</p>
                    <p class="text-sm text-gray-600">Total Ujian</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-200 stats-card">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujian->filter(fn($u) => $u->status === 'published')->count() }}</p>
                    <p class="text-sm text-gray-600">Published</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-200 stats-card">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-xl text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $ujian->sum(fn($u) => $u->soalUjian->count()) }}</p>
                    <p class="text-sm text-gray-600">Total Soal</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-200 table-container">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <tr>
                        <th class="text-left font-semibold">Judul Ujian</th>
                        <th class="text-left font-semibold">Mapel</th>
                        <th class="text-center font-semibold">Mode</th>
                        <th class="text-center font-semibold">Soal</th>
                        <th class="text-center font-semibold">Durasi</th>
                        <th class="text-center font-semibold">Status</th>
                        <th class="text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ujian as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td>
                                <p class="font-semibold text-gray-900">{{ $u->judul }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $u->created_at->format('d M Y') }}
                                </p>
                            </td>
                            <td>
                                <span class="flex items-center gap-2 text-gray-700 text-sm">
                                    <i class="fas {{ $u->mapel->icon ?? 'fa-book' }} text-blue-600"></i>
                                    {{ $u->mapel->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $u->mode === 'latihan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($u->mode) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-semibold text-sm">
                                    {{ $u->soalUjian->count() }}
                                </span>
                            </td>
                            <td class="text-center text-sm text-gray-700 font-medium">
                                @if($u->mode === 'ujian')
                                    {{ $u->durasi_menit }} mnt
                                </if>
                                @else
                                    <span class="text-green-600">∞</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $u->status == 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('ujian.rekap', $u->id) }}" 
                                       class="px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-xs font-bold flex items-center gap-1"
                                       title="Rekap Nilai">
                                        <i class="fas fa-chart-bar"></i> Rekap
                                    </a>

                                    <a href="{{ route('ujian.hasil', $u->id) }}" 
                                       class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700" 
                                       title="Lihat Hasil">
                                        <i class="fas fa-chart-line text-sm"></i>
                                    </a>
                                    <a href="{{ route('ujian.edit', $u->id) }}" 
                                       class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" 
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('ujian.destroy', $u->id) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Yakin hapus ujian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700" 
                                                title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center">
                                <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                                <p class="text-gray-600 font-semibold text-lg mb-1">Belum ada ujian</p>
                                <p class="text-gray-500 text-sm mb-4">Mulai buat ujian pertama Anda</p>
                                <a href="{{ route('ujian.create') }}" 
                                   class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold">
                                    <i class="fas fa-plus mr-2"></i> Buat Ujian
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ujian->hasPages())
        <div class="mt-4">
            {{ $ujian->links() }}
        </div>
    @endif
</div>
@endsection