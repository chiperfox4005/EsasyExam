@extends('layouts.app')

@section('title', 'Kelola Siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Siswa ‍🎓</h1>
            <p class="text-gray-600 mt-1">Manajemen data siswa</p>
        </div>
        <a href="{{ route('siswa.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-3xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus mr-2"></i> Tambah Siswa
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-3xl flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="p-4 rounded-l-2xl">Nama</th>
                        <th class="p-4">NISN</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Telepon</th>
                        <th class="p-4 rounded-r-2xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswas as $s)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-900">{{ $s->name }}</td>
                            <td class="p-4 text-gray-600">{{ $s->nisn }}</td>
                            <td class="p-4 text-gray-600">{{ $s->kelas->nama ?? '-' }}</td>
                            <td class="p-4 text-gray-600">{{ $s->phone ?? '-' }}</td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('siswa.edit', $s->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $siswas->links() }}
        </div>
    </div>
</div>
@endsection