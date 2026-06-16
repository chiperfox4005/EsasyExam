@extends('layouts.app')

@section('title', 'Buat Paket Latihan Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <h1 class="text-3xl font-bold mb-2">📦 Buat Paket Latihan Baru</h1>
        <p class="text-purple-100">Isi informasi paket latihan</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 p-6">
        <form action="{{ route('guru.paket-latihan.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Judul -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Paket <span class="text-red-500">*</span></label>
                <input type="text" name="judul" required
                       class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="Contoh: Bahasa Indonesia - Paket 1 - Bab Tumbuhan">
            </div>

            <!-- Mapel -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="mapel_id" required
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500"
                          placeholder="Deskripsi singkat tentang paket latihan ini..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 font-bold shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i> Simpan & Lanjut ke Kelola Soal
                </button>
                <a href="{{ route('guru.paket-latihan.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-bold transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection