@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Ujian ✏️</h1>
            <p class="text-gray-700 mt-1">Perbarui informasi ujian</p>
        </div>
        <a href="{{ route('ujian.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('ujian.update', $ujian->id) }}" method="POST" class="bg-white rounded-3xl p-6 shadow-xl border-2 border-gray-200">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-2">Judul Ujian</label>
                <input type="text" name="judul" value="{{ old('judul', $ujian->judul) }}" required 
                       class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl">
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id }}" {{ $ujian->mapel_id == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Tipe</label>
                    <select name="tipe" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl">
                        <option value="quiz" {{ $ujian->tipe == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="uts" {{ $ujian->tipe == 'uts' ? 'selected' : '' }}>UTS</option>
                        <option value="uas" {{ $ujian->tipe == 'uas' ? 'selected' : '' }}>UAS</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-900 mb-2">Mode</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="latihan" {{ $ujian->mode == 'latihan' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50">
                            <p class="font-bold text-gray-900">Latihan</p>
                            <p class="text-xs text-gray-600">Tanpa waktu</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="ujian" {{ $ujian->mode == 'ujian' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50">
                            <p class="font-bold text-gray-900">Ujian</p>
                            <p class="text-xs text-gray-600">Dengan waktu</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold shadow-lg">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('ujian.index') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection