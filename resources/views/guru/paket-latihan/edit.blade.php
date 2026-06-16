@extends('layouts.app')

@section('title', 'Edit Paket - ' . $paket->judul)

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <h1 class="text-3xl font-bold mb-2">✏️ Edit Paket Latihan</h1>
        <p class="text-purple-100">{{ $paket->judul }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 p-6">
        <form action="{{ route('guru.paket-latihan.update', $paket->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Paket <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ $paket->judul }}" required
                       class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Mapel -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="mapel_id" required
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500">
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" {{ $paket->mapel_id == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500">{{ $paket->deskripsi }}</textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $paket->is_active ? 'checked' : '' }}
                           class="w-5 h-5 text-purple-600 rounded">
                    <div>
                        <p class="font-bold text-gray-900">Aktif</p>
                        <p class="text-xs text-gray-500">Paket akan muncul untuk siswa</p>
                    </div>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 font-bold shadow-lg">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('guru.paket-latihan.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-bold">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection