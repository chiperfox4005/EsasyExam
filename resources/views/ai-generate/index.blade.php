@extends('layouts.app')

@section('title', 'AI Generate Soal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fadeIn" x-data="aiGenerate()">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-lg shadow-purple-500/20">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-robot text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">AI Generate Soal 🤖</h1>
                    <p class="text-purple-100">Generate soal otomatis dengan kecerdasan buatan</p>
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
    </div>

    <!-- Form Generate -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Parameter Generate</h3>
        
        <form @submit.prevent="generateSoal" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select id="mapel_id" x-model="form.mapel_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Soal</label>
                    <input type="number" id="jumlah" x-model="form.jumlah" min="1" max="20" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 20 soal per generate</p>
                </div>
            </div>

            <div>
                <label for="topik" class="block text-sm font-medium text-gray-700 mb-1">Topik / Materi</label>
                <textarea id="topik" x-model="form.topik" rows="3" required placeholder="Contoh: Persamaan linear satu variabel, Hukum Newton, Teks eksposisi..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Soal</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe" value="pg" x-model="form.tipe" class="peer sr-only" checked>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-center">
                                <p class="font-semibold text-sm">PG</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe" value="essay" x-model="form.tipe" class="peer sr-only">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-center">
                                <p class="font-semibold text-sm">Essay</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe" value="campuran" x-model="form.tipe" class="peer sr-only">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-center">
                                <p class="font-semibold text-sm">Campuran</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Level Kesulitan</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="mudah" x-model="form.level" class="peer sr-only">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all text-center">
                                <p class="font-semibold text-sm">Mudah</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="sedang" x-model="form.level" class="peer sr-only" checked>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all text-center">
                                <p class="font-semibold text-sm">Sedang</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="sulit" x-model="form.level" class="peer sr-only">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 transition-all text-center">
                                <p class="font-semibold text-sm">Sulit</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="campuran" x-model="form.level" class="peer sr-only">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                                <p class="font-semibold text-sm">Campuran</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="loading" class="w-full px-6 py-3 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 transition-colors font-semibold shadow-lg shadow-purple-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!loading">
                    <i class="fas fa-magic mr-2"></i> Generate Soal dengan AI
                </span>
                <span x-show="loading">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Sedang generate...
                </span>
            </button>
        </form>
    </div>

    <!-- Error Message -->
    <div x-show="error" x-cloak class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-3xl">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span x-text="error"></span>
    </div>

    <!-- Preview Hasil Generate -->
    <div x-show="hasil.length > 0" x-cloak class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Preview Soal (<span x-text="hasil.length"></span> soal)</h3>
                <p class="text-sm text-gray-500">Review dan edit soal sebelum disimpan</p>
            </div>
            <button @click="saveSoal" :disabled="saving" class="px-6 py-2 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition-colors font-semibold disabled:opacity-50">
                <span x-show="!saving">
                    <i class="fas fa-save mr-2"></i> Simpan ke Bank Soal
                </span>
                <span x-show="saving">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...
                </span>
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="(soal, index) in hasil" :key="index">
                <div class="border border-gray-200 rounded-2xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-700 font-semibold">
                            Soal #<span x-text="index + 1"></span>
                        </span>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 text-xs rounded-full" :class="soal.tipe === 'pg' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'">
                                <span x-text="soal.tipe.toUpperCase()"></span>
                            </span>
                            <span class="px-2 py-1 text-xs rounded-full" :class="{
                                'bg-green-100 text-green-700': soal.level === 'mudah',
                                'bg-orange-100 text-orange-700': soal.level === 'sedang',
                                'bg-red-100 text-red-700': soal.level === 'sulit'
                            }">
                                <span x-text="soal.level"></span>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Pertanyaan</label>
                        <textarea x-model="soal.pertanyaan" rows="2" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"></textarea>
                    </div>

                    <!-- Opsi untuk PG -->
                    <template x-if="soal.tipe === 'pg'">
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <template x-for="opsi in ['a', 'b', 'c', 'd', 'e']" :key="opsi">
                                <div x-show="soal['opsi_' + opsi]">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Opsi <span x-text="opsi.toUpperCase()"></span></label>
                                    <input type="text" x-model="soal['opsi_' + opsi]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                </div>
                            </template>
                        </div>
                    </template>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jawaban Benar</label>
                        <input type="text" x-model="soal.jawaban" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <button @click="hasil.splice(index, 1)" class="text-red-600 hover:text-red-700 text-sm font-semibold">
                        <i class="fas fa-trash mr-1"></i> Hapus Soal Ini
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function aiGenerate() {
    return {
        form: {
            mapel_id: '',
            topik: '',
            jumlah: 5,
            tipe: 'pg',
            level: 'sedang'
        },
        loading: false,
        saving: false,
        error: '',
        hasil: [],
        
        async generateSoal() {
            this.loading = true;
            this.error = '';
            this.hasil = [];
            
            try {
                const response = await fetch('{{ route("ai-generate.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.hasil = data.soal;
                    this.mapelId = data.mapel_id;
                } else {
                    this.error = data.error || 'Gagal generate soal';
                }
            } catch (err) {
                this.error = 'Terjadi kesalahan: ' + err.message;
            } finally {
                this.loading = false;
            }
        },
        
        async saveSoal() {
            if (!confirm('Simpan semua soal ini ke Bank Soal? (Status: Draft)')) return;
            
            this.saving = true;
            
            try {
                const form = new FormData();
                form.append('mapel_id', this.mapelId);
                
                this.hasil.forEach((soal, index) => {
                    form.append(`soal[${index}][tipe]`, soal.tipe);
                    form.append(`soal[${index}][pertanyaan]`, soal.pertanyaan);
                    form.append(`soal[${index}][opsi_a]`, soal.opsi_a || '');
                    form.append(`soal[${index}][opsi_b]`, soal.opsi_b || '');
                    form.append(`soal[${index}][opsi_c]`, soal.opsi_c || '');
                    form.append(`soal[${index}][opsi_d]`, soal.opsi_d || '');
                    form.append(`soal[${index}][opsi_e]`, soal.opsi_e || '');
                    form.append(`soal[${index}][jawaban]`, soal.jawaban);
                    form.append(`soal[${index}][level]`, soal.level);
                });
                
                const response = await fetch('{{ route("ai-generate.save") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: form
                });
                
                if (response.ok) {
                    window.location.href = '{{ route("bank-soal.index") }}';
                } else {
                    alert('Gagal menyimpan soal');
                }
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection