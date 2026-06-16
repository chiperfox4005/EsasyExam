@extends('layouts.app')

@section('title', 'Tambah Soal ke Bank Soal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fadeIn" x-data="bankSoalForm()">
    
    <div class="bg-gradient-to-r {{ isset($soalListExisting) ? 'from-orange-500 to-red-600' : 'from-blue-500 to-indigo-600' }} rounded-3xl p-6 text-white shadow-xl">
        <h1 class="text-3xl font-bold mb-2">
            @if(isset($soalListExisting))
                📝 Edit Soal Bank
            @else
                📚 Tambah Soal Baru
            @endif
        </h1>
        <p class="text-blue-100">
            @if(isset($soalListExisting))
                Mengedit {{ $soalListExisting->count() }} soal dari mapel {{ $soal->mapel->nama ?? '' }}
            @else
                Tambahkan soal ke bank soal untuk digunakan di berbagai ujian
            @endif
        </p>
    </div>

    <form action="{{ route('bank-soal.store') }}" method="POST" id="bankSoalForm" class="space-y-6" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">1</span>
                <span>Informasi Soal</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">📖 Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">🎯 Level Kesulitan</label>
                    <select name="level" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl">
                        <option value="mudah">😊 Mudah</option>
                        <option value="sedang" selected>😐 Sedang</option>
                        <option value="sulit">😅 Sulit</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">👁️ Tampilan Jawaban</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="tampilkan_jawaban" value="immediate" x-model="tampilkanJawaban" class="peer sr-only" checked>
                            <div class="p-5 border-2 border-gray-300 rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-eye text-xl text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Langsung</p>
                                        <p class="text-xs text-gray-600">Jawaban tampil setelah submit</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tampilkan_jawaban" value="after_submit" x-model="tampilkanJawaban" class="peer sr-only">
                            <div class="p-5 border-2 border-gray-300 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check-circle text-xl text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Setelah Submit</p>
                                        <p class="text-xs text-gray-600">Jawaban tampil setelah ujian selesai</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">2</span>
                    <span>Buat Soal</span>
                </h3>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Total Soal:</span>
                    <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full font-bold shadow-lg" x-text="soalList.length"></span>
                </div>
            </div>

            <div id="formTambahSoal" class="border-2 border-blue-200 rounded-2xl p-6 bg-gradient-to-br from-blue-50 to-indigo-50 mb-6">
                <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-blue-600"></i>
                    <span>Tambah Soal #<span x-text="soalList.length + 1"></span></span>
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">📝 Tipe Soal</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" x-model="currentSoal.tipe" value="pg" class="peer sr-only">
                                <div class="p-4 border-2 border-gray-300 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center transition-all">
                                    <i class="fas fa-list-ol text-3xl text-blue-600 mb-2"></i>
                                    <p class="font-bold text-gray-900">Pilihan Ganda</p>
                                    <p class="text-xs text-gray-600 mt-1">4 opsi (A, B, C, D)</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="currentSoal.tipe" value="essay" class="peer sr-only">
                                <div class="p-4 border-2 border-gray-300 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 text-center transition-all">
                                    <i class="fas fa-pen text-3xl text-purple-600 mb-2"></i>
                                    <p class="font-bold text-gray-900">Essay</p>
                                    <p class="text-xs text-gray-600 mt-1">Jawaban uraian</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">❓ Pertanyaan</label>
                        <textarea x-model="currentSoal.pertanyaan" rows="3" 
                                  class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-blue-500"
                                  :class="errors.pertanyaan ? 'border-red-500 bg-red-50' : ''"
                                  placeholder="Tulis pertanyaan di sini..."></textarea>
                        <p x-show="errors.pertanyaan" class="text-red-600 text-xs mt-1 font-semibold" x-text="errors.pertanyaan"></p>
                        
                        <div class="mt-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">📷 Gambar Soal (Opsional)</label>
                            <label class="flex items-center gap-4 cursor-pointer">
                                <div class="flex-1 border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium">Klik untuk upload gambar soal</p>
                                    <p class="text-xs text-gray-500 mt-1">Max 2MB (JPG, PNG)</p>
                                </div>
                                <input type="file" accept="image/*" @change="previewImage($event, 'previewSoal')" class="hidden">
                                <img id="previewSoal" class="hidden max-h-32 rounded-xl border-2 border-gray-200 shadow-md">
                            </label>
                        </div>
                    </div>

                    <template x-if="currentSoal.tipe === 'pg'">
                        <div class="space-y-3">
                            <h5 class="font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-list text-blue-600"></i>
                                Opsi Jawaban
                            </h5>
                            
                            <template x-for="(opsi, idx) in currentSoal.opsi" :key="'form-opsi-' + idx">
                                <div class="bg-white border-2 border-gray-200 rounded-xl p-4 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-md"
                                              :class="[
                                                  'bg-gradient-to-br',
                                                  idx === 0 ? 'from-blue-500 to-blue-600' : 
                                                  idx === 1 ? 'from-green-500 to-green-600' : 
                                                  idx === 2 ? 'from-purple-500 to-purple-600' : 
                                                  'from-orange-500 to-orange-600'
                                              ]"
                                              x-text="String.fromCharCode(65 + idx)"></span>
                                        <input type="text" x-model="opsi.teks" 
                                               class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" 
                                               :placeholder="'Tulis opsi ' + String.fromCharCode(65 + idx) + '...'">
                                        <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-green-50 transition-all"
                                               :class="currentSoal.jawaban === String.fromCharCode(65 + idx) ? 'bg-green-100 border-2 border-green-400' : ''">
                                            <input type="radio" :name="'jawaban_pg_' + soalList.length" 
                                                   :value="String.fromCharCode(65 + idx)" 
                                                   x-model="currentSoal.jawaban" 
                                                   class="w-5 h-5 text-green-600 cursor-pointer">
                                            <span class="text-xs font-bold" :class="currentSoal.jawaban === String.fromCharCode(65 + idx) ? 'text-green-700' : 'text-gray-600'">Benar</span>
                                        </label>
                                    </div>
                                    <div class="ml-13">
                                        <label class="block text-xs font-bold text-gray-600 mb-2">📷 Gambar Opsi (Opsional)</label>
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <div class="flex-1 border-2 border-dashed border-gray-300 rounded-lg p-3 text-center hover:border-blue-500 hover:bg-blue-50 transition-all">
                                                <i class="fas fa-image text-xl text-gray-400"></i>
                                                <p class="text-xs text-gray-600 mt-1">Upload gambar</p>
                                            </div>
                                            <input type="file" accept="image/*" @change="previewImage($event, 'previewOpsi' + String.fromCharCode(65 + idx))" class="hidden">
                                            <img :id="'previewOpsi' + String.fromCharCode(65 + idx)" class="hidden max-h-20 rounded-lg border-2 border-gray-200 shadow-sm">
                                        </label>
                                    </div>
                                </div>
                            </template>
                            
                            <div class="p-3 bg-green-50 border-2 border-green-200 rounded-xl">
                                <p class="text-xs text-green-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>✓ Jawaban Benar:</strong> Klik tombol "Benar" di sebelah opsi yang benar
                                </p>
                            </div>
                        </div>
                    </template>

                    <template x-if="currentSoal.tipe === 'essay'">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">📝 Kunci Jawaban</label>
                            <textarea x-model="currentSoal.jawaban" rows="4" 
                                      class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl resize-none"
                                      :class="errors.jawaban ? 'border-red-500 bg-red-50' : ''"
                                      placeholder="Tulis kunci jawaban..."></textarea>
                            <p x-show="errors.jawaban" class="text-red-600 text-xs mt-1 font-semibold" x-text="errors.jawaban"></p>
                        </div>
                    </template>

                    <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4">
                        <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-yellow-600"></i>
                            Penjelasan Jawaban (Opsional tapi disarankan)
                        </label>
                        <textarea x-model="currentSoal.penjelasan" rows="3" 
                                  class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl resize-none"
                                  placeholder="Jelaskan mengapa jawaban ini benar..."></textarea>
                        <p class="text-xs text-yellow-800 mt-1">💡 Penjelasan akan membantu siswa memahami materi</p>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="tambahSoal()" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i> Simpan & Lanjutkan
                        </button>
                        <button type="button" @click="resetForm()" 
                                class="px-6 py-3 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold transition-all">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-6">
                <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-3" x-show="soalList.length > 0">
                    <i class="fas fa-list-ol text-green-600"></i>
                    <span>Daftar Soal yang Sudah Dibuat</span>
                </h4>
                
                <template x-for="(soal, index) in soalList" :key="'soal-' + index">
                    <div class="border-2 border-gray-200 rounded-2xl p-5 hover:shadow-lg transition-all bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-lg" x-text="index + 1"></span>
                                <span class="px-3 py-1 text-xs font-bold rounded-full" 
                                      :class="soal.tipe === 'pg' ? 'bg-blue-100 text-blue-700 border-2 border-blue-300' : 'bg-purple-100 text-purple-700 border-2 border-purple-300'" 
                                      x-text="soal.tipe === 'pg' ? 'PILIHAN GANDA' : 'ESSAY'"></span>
                            </div>
                            <button type="button" @click="hapusSoal(index)" class="text-red-600 hover:text-red-700 text-sm font-bold flex items-center gap-1 px-3 py-1 rounded-lg hover:bg-red-50">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        
                        <p class="text-gray-900 font-bold text-base mb-3" x-text="soal.pertanyaan"></p>
                        
                        <template x-if="soal.gambarSoal">
                            <img :src="soal.gambarSoal" class="max-h-64 rounded-xl border-2 border-gray-200 shadow-md mb-3">
                        </template>
                        
                        <template x-if="soal.tipe === 'pg'">
                            <div class="space-y-2">
                                <template x-for="(opsi, idx) in soal.opsi" :key="'opsi-' + idx">
                                    <div>
                                        <div class="flex items-center gap-3 p-2 rounded-lg" 
                                             :class="String.fromCharCode(65 + idx) === soal.jawaban ? 'bg-green-50 border-2 border-green-300' : 'bg-white border-2 border-gray-200'">
                                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold" 
                                                  :class="String.fromCharCode(65 + idx) === soal.jawaban ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'" 
                                                  x-text="String.fromCharCode(65 + idx)"></span>
                                            <span class="flex-1 text-sm text-gray-700" x-text="opsi.teks"></span>
                                            <span x-show="String.fromCharCode(65 + idx) === soal.jawaban" class="text-green-600 font-bold text-xs">✓ Benar</span>
                                        </div>
                                        <template x-if="opsi.gambar">
                                            <img :src="opsi.gambar" class="max-h-32 rounded-lg border-2 border-gray-200 shadow-sm ml-10 mt-1 mb-2">
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                        
                        <template x-if="soal.tipe === 'essay'">
                            <div class="mt-3 p-3 bg-purple-50 border-2 border-purple-200 rounded-xl">
                                <p class="text-xs font-bold text-purple-700 mb-1">💡 Kunci Jawaban:</p>
                                <p class="text-sm text-gray-800" x-text="soal.jawaban"></p>
                            </div>
                        </template>
                        
                        <template x-if="soal.penjelasan">
                            <div class="mt-3 p-3 bg-yellow-50 border-2 border-yellow-200 rounded-xl">
                                <p class="text-xs font-bold text-yellow-700 mb-1">📝 Penjelasan:</p>
                                <p class="text-sm text-gray-800" x-text="soal.penjelasan"></p>
                            </div>
                        </template>
                    </div>
                </template>
                
                <div x-show="soalList.length === 0" class="p-8 bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clipboard-list text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada soal</p>
                    <p class="text-sm text-gray-500 mt-1">Silakan buat soal pertama Anda di form atas ☝️</p>
                </div>
            </div>
        </div>

        <div id="soalInputsContainer"></div>

        <div class="flex gap-3 sticky bottom-4">
            <button type="button" onclick="submitBankSoal()" 
                    class="flex-1 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 font-bold text-lg shadow-xl shadow-green-500/30 transition-all transform hover:scale-105">
                <i class="fas fa-save mr-2"></i> Simpan ke Bank Soal
            </button>
            <a href="{{ route('bank-soal.index') }}" 
               class="px-8 py-4 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold text-lg transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
<script>
window.soalListData = [];

function bankSoalForm() {
    return {
        tampilkanJawaban: 'immediate',
        soalList: [],
        errors: {},
        isEditMode: {{ isset($soalListExisting) ? 'true' : 'false' }},
        editMapelId: {{ isset($soal) ? $soal->mapel_id : 'null' }},
        currentSoal: {
            tipe: 'pg',
            pertanyaan: '',
            gambarSoal: null,
            jawaban: '',
            penjelasan: '',
            opsi: [
                { teks: '', gambar: null },
                { teks: '', gambar: null },
                { teks: '', gambar: null },
                { teks: '', gambar: null }
            ]
        },
        
        init() {
            @if(isset($soalListExisting) && $soalListExisting->count() > 0)
                const existingData = @json($soalListExisting);
                
                if (existingData.length > 0) {
                    const mapelSelect = document.querySelector('select[name="mapel_id"]');
                    if (mapelSelect) {
                        mapelSelect.value = existingData[0].mapel_id;
                    }
                    
                    const levelSelect = document.querySelector('select[name="level"]');
                    if (levelSelect) {
                        levelSelect.value = existingData[0].level;
                    }
                }
                
                existingData.forEach(soal => {
                    const soalObj = {
                        id: soal.id,
                        tipe: soal.tipe,
                        pertanyaan: soal.pertanyaan,
                        gambarSoal: soal.gambar_soal || null,
                        jawaban: soal.jawaban || '',
                        penjelasan: soal.penjelasan || '',
                        opsi: []
                    };
                    
                    if (soal.tipe === 'pg') {
                        soalObj.opsi = [
                            { teks: soal.opsi_a || '', gambar: soal.gambar_opsi_a || null },
                            { teks: soal.opsi_b || '', gambar: soal.gambar_opsi_b || null },
                            { teks: soal.opsi_c || '', gambar: soal.gambar_opsi_c || null },
                            { teks: soal.opsi_d || '', gambar: soal.gambar_opsi_d || null }
                        ];
                    }
                    
                    this.soalList.push(soalObj);
                });
                
                window.soalListData = JSON.parse(JSON.stringify(this.soalList));
                
                setTimeout(() => {
                    alert('📝 Mode Edit: ' + this.soalList.length + ' soal dari mapel ini sudah dimuat. Anda bisa edit, tambah, atau hapus soal!');
                }, 300);
            @endif
        },
        
        tambahSoal() {
            this.errors = {};
            
            if (!this.currentSoal.pertanyaan.trim()) {
                this.errors = { pertanyaan: 'Pertanyaan harus diisi!' };
                return;
            }
            
            if (this.currentSoal.tipe === 'pg') {
                for (let i = 0; i < 4; i++) {
                    if (!this.currentSoal.opsi[i].teks.trim()) {
                        this.errors = { [`opsi_${i}`]: `Opsi ${String.fromCharCode(65 + i)} harus diisi!` };
                        return;
                    }
                }
            } else {
                if (!this.currentSoal.jawaban.trim()) {
                    this.errors = { jawaban: 'Kunci jawaban harus diisi!' };
                    return;
                }
            }
            
            const soalBaru = {
                tipe: this.currentSoal.tipe,
                pertanyaan: this.currentSoal.pertanyaan,
                gambarSoal: this.currentSoal.gambarSoal,
                jawaban: this.currentSoal.jawaban,
                penjelasan: this.currentSoal.penjelasan,
                opsi: this.currentSoal.tipe === 'pg' ? [
                    { teks: this.currentSoal.opsi[0].teks, gambar: this.currentSoal.opsi[0].gambar },
                    { teks: this.currentSoal.opsi[1].teks, gambar: this.currentSoal.opsi[1].gambar },
                    { teks: this.currentSoal.opsi[2].teks, gambar: this.currentSoal.opsi[2].gambar },
                    { teks: this.currentSoal.opsi[3].teks, gambar: this.currentSoal.opsi[3].gambar }
                ] : []
            };
            
            this.soalList.push(soalBaru);
            window.soalListData = JSON.parse(JSON.stringify(this.soalList));
            
            this.resetForm();
            
            setTimeout(() => {
                const formElement = document.getElementById('formTambahSoal');
                if (formElement) {
                    formElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        },
        
        hapusSoal(index) {
            if (confirm('Yakin ingin hapus soal ini?')) {
                this.soalList.splice(index, 1);
                window.soalListData = JSON.parse(JSON.stringify(this.soalList));
            }
        },
        
        resetForm() {
            this.currentSoal = {
                tipe: 'pg',
                pertanyaan: '',
                gambarSoal: null,
                jawaban: '',
                penjelasan: '',
                opsi: [
                    { teks: '', gambar: null },
                    { teks: '', gambar: null },
                    { teks: '', gambar: null },
                    { teks: '', gambar: null }
                ]
            };
            this.errors = {};
            
            document.querySelectorAll('[id^="preview"]').forEach(img => {
                img.classList.add('hidden');
                img.src = '';
            });
        },
        
        previewImage(event, previewId) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('❌ Ukuran file maksimal adalah 2MB!');
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.getElementById(previewId);
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                    
                    if (previewId === 'previewSoal') {
                        this.currentSoal.gambarSoal = e.target.result;
                    } else if (previewId.startsWith('previewOpsi')) {
                        const letter = previewId.replace('previewOpsi', '');
                        const idx = letter.charCodeAt(0) - 65;
                        if (idx >= 0 && idx < 4) {
                            this.currentSoal.opsi[idx].gambar = e.target.result;
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

// Helper untuk membuat input hidden
function addHiddenInput(container, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value ?? '';
    container.appendChild(input);
}

function submitBankSoal() {
    let soalList = [];
    
    try {
        const app = Alpine.$data(document.querySelector('[x-data]'));
        if (app && app.soalList) {
            soalList = app.soalList;
        }
    } catch (e) {
        if (window.soalListData && window.soalListData.length > 0) {
            soalList = window.soalListData;
        }
    }
    
    if (!soalList || soalList.length === 0) {
        alert('❌ Minimal 1 soal harus ditambahkan!');
        return;
    }
    
    const mapelId = document.querySelector('select[name="mapel_id"]');
    if (!mapelId || !mapelId.value) {
        alert('❌ Mata pelajaran harus dipilih!');
        return;
    }
    
    const levelSelect = document.querySelector('select[name="level"]');
    if (!levelSelect || !levelSelect.value) {
        alert('❌ Level kesulitan harus dipilih!');
        return;
    }
    
    const container = document.getElementById('soalInputsContainer');
    container.innerHTML = '';
    
    // ✅ CEK EDIT MODE
    const isEditMode = soalList.some(s => s.id);
    if (isEditMode) {
        addHiddenInput(container, 'edit_mode', '1');
        addHiddenInput(container, 'mapel_id_edit', mapelId.value);
    }
    
    soalList.forEach((soal, index) => {
        // ✅ KIRIM ID JIKA ADA (untuk update)
        if (soal.id) {
            addHiddenInput(container, `soal_list[${index}][id]`, soal.id);
        }
        
        addHiddenInput(container, `soal_list[${index}][tipe]`, soal.tipe);
        addHiddenInput(container, `soal_list[${index}][pertanyaan]`, soal.pertanyaan);
        addHiddenInput(container, `soal_list[${index}][jawaban]`, soal.jawaban);
        addHiddenInput(container, `soal_list[${index}][penjelasan]`, soal.penjelasan || '');
        
        // Gambar Soal (jika ada)
        if (soal.gambarSoal && soal.gambarSoal.startsWith('data:image')) {
            addHiddenInput(container, `soal_list[${index}][gambar_soal]`, soal.gambarSoal);
        }
        
        // ✅ OPSI PG - FORMAT YANG BENAR: opsi[A], opsi[B], opsi[C], opsi[D]
        if (soal.tipe === 'pg' && soal.opsi) {
            soal.opsi.forEach((opsi, oIdx) => {
                const label = String.fromCharCode(65 + oIdx); // A, B, C, D
                addHiddenInput(container, `soal_list[${index}][opsi][${label}]`, opsi.teks);
                
                // Gambar Opsi (jika ada)
                if (opsi.gambar && opsi.gambar.startsWith('data:image')) {
                    addHiddenInput(container, `soal_list[${index}][gambar_opsi][${label}]`, opsi.gambar);
                }
            });
        }
    });
    
    document.getElementById('bankSoalForm').submit();
}
</script>
@endsection