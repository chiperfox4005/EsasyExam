@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tambah Kelas 🏫</h1>
            <p class="text-gray-600 mt-1">Isi form di bawah untuk menambah kelas baru</p>
        </div>
        <a href="{{ route('kelas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('kelas.store') }}" method="POST" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                <input type="number" name="tingkat" id="tingkat" value="{{ old('tingkat') }}" min="1" max="12" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('tingkat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select name="tipe" id="tipe" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="formal" {{ old('tipe') == 'formal' ? 'selected' : '' }}>Formal (Sekolah)</option>
                    <option value="virtual" {{ old('tipe') == 'virtual' ? 'selected' : '' }}>Virtual (Les Online)</option>
                </select>
            </div>
            <div>
                <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" id="wali_kelas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}" {{ old('wali_kelas_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i> Simpan
            </button>
            <a href="{{ route('kelas.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection