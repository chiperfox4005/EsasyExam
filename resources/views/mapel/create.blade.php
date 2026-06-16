@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tambah Mata Pelajaran 📚</h1>
            <p class="text-gray-600 mt-1">Isi form di bawah untuk menambah mata pelajaran baru</p>
        </div>
        <a href="{{ route('mapel.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('mapel.store') }}" method="POST" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mapel</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('kode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon', 'fa-book') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Contoh: fa-book, fa-calculator, fa-flask</p>
            </div>
            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Umum / Semua Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
                <select name="guru_id" id="guru_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i> Simpan
            </button>
            <a href="{{ route('mapel.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection