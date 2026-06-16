@extends('layouts.app')

@section('title', 'Riwayat Ujian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Ujian 📜</h1>
        <p class="text-gray-700 mt-1">Semua ujian yang pernah kamu kerjakan</p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border-2 border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                    <th class="p-4 text-left font-bold">Ujian</th>
                    <th class="p-4 text-left font-bold">Mapel</th>
                    <th class="p-4 text-center font-bold">Nilai</th>
                    <th class="p-4 text-center font-bold">Status</th>
                    <th class="p-4 text-center font-bold">Tanggal</th>
                    <th class="p-4 text-center font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($hasilList as $hasil)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-semibold text-gray-900">{{ $hasil->ujian->judul }}</td>
                        <td class="p-4 text-gray-700">{{ $hasil->ujian->mapel->nama }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold {{ $hasil->nilai >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ number_format($hasil->nilai, 1) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">
                                {{ ucfirst($hasil->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-center text-sm text-gray-600">{{ $hasil->created_at->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('siswa.ujian.hasil', $hasil->ujian_id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-semibold">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-600">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="font-semibold">Belum ada riwayat ujian</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $hasilList->links() }}
    </div>
</div>
@endsection