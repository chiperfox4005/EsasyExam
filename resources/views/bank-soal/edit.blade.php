@extends('layouts.app')

@section('title', 'Edit Soal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Soal 📝</h1>
            <p class="text-gray-600 mt-1">Update informasi soal</p>
        </div>
        <a href="{{ route('bank-soal.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('bank-soal.update', $soal->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100" x-data="soalForm('{{ $soal->tipe }}')">
        @csrf
        @method('PUT')

        <!-- Tipe Soal -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Soal</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="pg" x-model="tipe" class="peer sr-only">
                    <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-list-ol text-2xl text-blue-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Pilihan Ganda</p>
                                <p class="text-xs text-gray-500">5 opsi jawaban</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="essay" x-model="tipe" class="peer sr-only">
                    <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-pen text-2xl text-purple-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Essay</p>
                                <p class="text-xs text-gray-500">Jawaban uraian</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @foreach($mapelList as $m)
                        <option value="{{ $m->id }}" {{ $soal->mapel_id == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level Kesulitan</label>
                <select name="level" id="level" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="mudah" {{ $soal->level == 'mudah' ? 'selected' : '' }}>Mudah</option>
                    <option value="sedang" {{ $soal->level == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="sulit" {{ $soal->level == 'sulit' ? 'selected' : '' }}>Sulit</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
            <textarea name="pertanyaan" id="pertanyaan" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
        </div>

        @if($soal->gambar_soal)
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
                <img src="{{ asset('storage/' . $soal->gambar_soal) }}" class="max-h-48 rounded-2xl">
            </div>
        @endif

        <div class="mt-4">
            <label for="gambar_soal" class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar (Opsional)</label>
            <input type="file" name="gambar_soal" id="gambar_soal" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Opsi PG -->
        <template x-if="tipe === 'pg'">
            <div class="mt-6 space-y-4">
                <h3 class="font-bold text-gray-900">Opsi Jawaban</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opsi A</label>
                        <input type="text" name="opsi_a" value="{{ old('opsi_a', $soal->opsi_a) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opsi B</label>
                        <input type="text" name="opsi_b" value="{{ old('opsi_b', $soal->opsi_b) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opsi C</label>
                        <input type="text" name="opsi_c" value="{{ old('opsi_c', $soal->opsi_c) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opsi D</label>
                        <input type="text" name="opsi_d" value="{{ old('opsi_d', $soal->opsi_d) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opsi E (Opsional)</label>
                        <input type="text" name="opsi_e" value="{{ old('opsi_e', $soal->opsi_e) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                    <select name="jawaban" id="jawaban" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="A" {{ $soal->jawaban == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ $soal->jawaban == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ $soal->jawaban == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ $soal->jawaban == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ $soal->jawaban == 'E' ? 'selected' : '' }}>E</option>
                    </select>
                </div>
            </div>
        </template>

        <!-- Jawaban Essay -->
        <template x-if="tipe === 'essay'">
            <div class="mt-6">
                <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-1">Kunci Jawaban / Panduan</label>
                <textarea name="jawaban" id="jawaban" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none">{{ old('jawaban', $soal->jawaban) }}</textarea>
            </div>
        </template>

        <!-- Status -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="status" value="draft" class="peer sr-only" {{ $soal->status == 'draft' ? 'checked' : '' }}>
                    <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-save text-xl text-yellow-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Draft</p>
                                <p class="text-xs text-gray-500">Simpan dulu</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="status" value="published" class="peer sr-only" {{ $soal->status == 'published' ? 'checked' : '' }}>
                    <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-xl text-green-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Publish</p>
                                <p class="text-xs text-gray-500">Langsung gunakan</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i> Update Soal
            </button>
            <a href="{{ route('bank-soal.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function soalForm(tipe) {
    return {
        tipe: tipe
    }
}
</script>
@endsection