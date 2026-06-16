@extends('layouts.app')

@section('title', 'Tambah Soal ke Bank Soal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fadeIn" x-data="bankSoalForm()">
    
    <!-- Header Ceria -->
    <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-database text-3xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Bank Soal Ceria 📚✨</h1>
                <p class="text-purple-100">Yuk buat soal-soal menarik untuk siswa!</p>
            </div>
        </div>
    </div>

    <form action="{{ route('bank-soal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Info Dasar -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">1</span>
                <span>Informasi Soal</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">📖 Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">🎯 Level Kesulitan</label>
                    <select name="level" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500">
                        <option value="mudah">😊 Mudah</option>
                        <option value="sedang" selected>😐 Sedang</option>
                        <option value="sulit">😅 Sulit</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Buat Soal (Step-by-Step) -->
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

            <!-- List Soal yang Sudah Dibuat -->
            <div class="space-y-4 mb-6">
                <template x-for="(soal, index) in soalList" :key="index">
                    <div class="border-2 border-gray-200 rounded-2xl p-5 hover:shadow-lg transition-all bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-lg" x-text="index + 1"></span>
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 border-2 border-blue-300">PILIHAN GANDA</span>
                            </div>
                            <button type="button" @click="hapusSoal(index)" class="text-red-600 hover:text-red-700 text-sm font-bold flex items-center gap-1 px-3 py-1 rounded-lg hover:bg-red-50 transition-all">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        <p class="text-gray-900 font-bold text-base mb-3" x-text="soal.pertanyaan"></p>
                        
                        <!-- Preview Opsi -->
                        <div class="space-y-2">
                            <template x-for="(opsi, idx) in soal.opsi" :key="idx">
                                <div class="flex items-center gap-3 p-2 rounded-lg" 
                                     :class="String.fromCharCode(65 + idx) === soal.jawaban ? 'bg-green-50 border-2 border-green-300' : 'bg-white border-2 border-gray-200'">
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold" 
                                          :class="String.fromCharCode(65 + idx) === soal.jawaban ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'" 
                                          x-text="String.fromCharCode(65 + idx)"></span>
                                    <span class="flex-1 text-sm text-gray-700" x-text="opsi.teks"></span>
                                    <span x-show="String.fromCharCode(65 + idx) === soal.jawaban" class="text-green-600 font-bold text-xs">✓ Benar</span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Penjelasan -->
                        <div x-show="soal.penjelasan" class="mt-3 p-3 bg-yellow-50 border-2 border-yellow-200 rounded-xl">
                            <p class="text-xs font-bold text-yellow-800 mb-1">💡 Penjelasan:</p>
                            <p class="text-sm text-gray-700" x-text="soal.penjelasan"></p>
                        </div>
                    </div>
                </template>
                
                <div x-show="soalList.length === 0" class="p-8 bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-plus-circle text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada soal</p>
                    <p class="text-sm text-gray-500 mt-1">Yuk buat soal pertama!</p>
                </div>
            </div>

            <!-- Form Tambah Soal Baru -->
            <div class="border-2 border-purple-200 rounded-2xl p-6 bg-gradient-to-br from-purple-50 to-pink-50">
                <h4 class="font-bold text-purple-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-purple-600"></i>
                    <span>Tambah Soal #<span x-text="soalList.length + 1"></span></span>
                </h4>
                
                <div class="space-y-4">
                    <!-- Pertanyaan dengan Upload Gambar -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">❓ Pertanyaan</label>
                        <textarea x-model="currentSoal.pertanyaan" rows="3" 
                                  class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-purple-500"
                                  :class="errors.pertanyaan ? 'border-red-500 bg-red-50' : ''"
                                  placeholder="Tulis pertanyaan yang menarik..."></textarea>
                        <p x-show="errors.pertanyaan" class="text-red-600 text-xs mt-1 font-semibold" x-text="errors.pertanyaan"></p>
                        
                        <!-- Upload Gambar Soal -->
                        <div class="mt-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">📷 Gambar Soal (Opsional)</label>
                            <label class="flex items-center gap-4 cursor-pointer">
                                <div class="flex-1 border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-purple-500 hover:bg-purple-50 transition-all">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium">Klik untuk upload gambar</p>
                                    <p class="text-xs text-gray-500 mt-1">Max 2MB (JPG, PNG)</p>
                                </div>
                                <input type="file" accept="image/*" @change="previewImage($event, 'previewSoal')" class="hidden">
                                <img id="previewSoal" class="hidden max-h-32 rounded-xl border-2 border-gray-200 shadow-md">
                            </label>
                        </div>
                    </div>

                    <!-- Opsi Jawaban A, B, C, D -->
                    <div class="space-y-3">
                        <h5 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-list text-blue-600"></i>
                            Opsi Jawaban (Pilih yang benar)
                        </h5>
                        
                        <!-- Opsi A -->
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border-2 transition-all"
                             :class="errors.opsi_0 ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-purple-300'">
                            <span class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">A</span>
                            <input type="text" x-model="currentSoal.opsi[0].teks" 
                                   class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500" 
                                   placeholder="Tulis opsi A...">
                            <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-green-50 transition-all"
                                   :class="currentSoal.jawaban === 'A' ? 'bg-green-100 border-2 border-green-400' : ''">
                                <input type="radio" name="jawaban_pg_temp" value="A" x-model="currentSoal.jawaban" class="w-5 h-5 text-green-600">
                                <span class="text-xs font-bold" :class="currentSoal.jawaban === 'A' ? 'text-green-700' : 'text-gray-600'">Benar</span>
                            </label>
                        </div>
                        <p x-show="errors.opsi_0" class="text-red-600 text-xs ml-14 -mt-2 font-semibold" x-text="errors.opsi_0"></p>
                        
                        <!-- Opsi B -->
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border-2 transition-all"
                             :class="errors.opsi_1 ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-purple-300'">
                            <span class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">B</span>
                            <input type="text" x-model="currentSoal.opsi[1].teks" 
                                   class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500" 
                                   placeholder="Tulis opsi B...">
                            <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-green-50 transition-all"
                                   :class="currentSoal.jawaban === 'B' ? 'bg-green-100 border-2 border-green-400' : ''">
                                <input type="radio" name="jawaban_pg_temp" value="B" x-model="currentSoal.jawaban" class="w-5 h-5 text-green-600">
                                <span class="text-xs font-bold" :class="currentSoal.jawaban === 'B' ? 'text-green-700' : 'text-gray-600'">Benar</span>
                            </label>
                        </div>
                        <p x-show="errors.opsi_1" class="text-red-600 text-xs ml-14 -mt-2 font-semibold" x-text="errors.opsi_1"></p>
                        
                        <!-- Opsi C -->
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border-2 transition-all"
                             :class="errors.opsi_2 ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-purple-300'">
                            <span class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">C</span>
                            <input type="text" x-model="currentSoal.opsi[2].teks" 
                                   class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500" 
                                   placeholder="Tulis opsi C...">
                            <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-green-50 transition-all"
                                   :class="currentSoal.jawaban === 'C' ? 'bg-green-100 border-2 border-green-400' : ''">
                                <input type="radio" name="jawaban_pg_temp" value="C" x-model="currentSoal.jawaban" class="w-5 h-5 text-green-600">
                                <span class="text-xs font-bold" :class="currentSoal.jawaban === 'C' ? 'text-green-700' : 'text-gray-600'">Benar</span>
                            </label>
                        </div>
                        <p x-show="errors.opsi_2" class="text-red-600 text-xs ml-14 -mt-2 font-semibold" x-text="errors.opsi_2"></p>
                        
                        <!-- Opsi D -->
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border-2 transition-all"
                             :class="errors.opsi_3 ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-purple-300'">
                            <span class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">D</span>
                            <input type="text" x-model="currentSoal.opsi[3].teks" 
                                   class="flex-1 px-4 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500" 
                                   placeholder="Tulis opsi D...">
                            <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-green-50 transition-all"
                                   :class="currentSoal.jawaban === 'D' ? 'bg-green-100 border-2 border-green-400' : ''">
                                <input type="radio" name="jawaban_pg_temp" value="D" x-model="currentSoal.jawaban" class="w-5 h-5 text-green-600">
                                <span class="text-xs font-bold" :class="currentSoal.jawaban === 'D' ? 'text-green-700' : 'text-gray-600'">Benar</span>
                            </label>
                        </div>
                        <p x-show="errors.opsi_3" class="text-red-600 text-xs ml-14 -mt-2 font-semibold" x-text="errors.opsi_3"></p>
                        
                        <div class="p-3 bg-green-50 border-2 border-green-200 rounded-xl">
                            <p class="text-xs text-green-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>✓ Jawaban Benar:</strong> Klik tombol "Benar" di sebelah opsi yang benar
                            </p>
                        </div>
                    </div>

                    <!-- Penjelasan Jawaban -->
                    <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4">
                        <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-yellow-500"></i>
                            Penjelasan Jawaban (Opsional tapi disarankan)
                        </label>
                        <textarea x-model="currentSoal.penjelasan" rows="3" 
                                  class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl resize-none"
                                  placeholder="Jelaskan mengapa jawaban ini benar..."></textarea>
                        <p class="text-xs text-yellow-800 mt-1">💡 Penjelasan akan membantu siswa memahami materi</p>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="tambahSoal()" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl hover:from-purple-700 hover:to-pink-700 font-bold shadow-lg shadow-purple-500/30 transition-all transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i> Simpan & Lanjutkan
                        </button>
                        <button type="button" @click="resetForm()" 
                                class="px-6 py-3 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold transition-all">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden inputs container -->
        <div id="soalInputsContainer"></div>

        <!-- Tombol Simpan ke Bank Soal -->
        <div class="flex gap-3 sticky bottom-4">
            <button type="button" onclick="submitBankSoal()" 
                    class="flex-1 px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl hover:from-purple-700 hover:to-pink-700 font-bold text-lg shadow-xl shadow-purple-500/30 transition-all transform hover:scale-105">
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
function bankSoalForm() {
    return {
        soalList: [],
        currentSoal: {
            pertanyaan: '',
            gambarSoal: null,
            opsi: [
                { teks: '', gambar: null },
                { teks: '', gambar: null },
                { teks: '', gambar: null },
                { teks: '', gambar: null }
            ],
            jawaban: 'A',
            penjelasan: ''
        },
        errors: {},
        
        tambahSoal() {
            this.errors = {};
            
            if (!this.currentSoal.pertanyaan.trim()) {
                this.errors = { pertanyaan: 'Pertanyaan harus diisi!' };
                return;
            }
            
            for (let i = 0; i < 4; i++) {
                if (!this.currentSoal.opsi[i].teks.trim()) {
                    this.errors = { [`opsi_${i}`]: `Opsi ${String.fromCharCode(65 + i)} harus diisi!` };
                    return;
                }
            }
            
            this.soalList.push({
                pertanyaan: this.currentSoal.pertanyaan,
                gambarSoal: this.currentSoal.gambarSoal,
                opsi: [
                    { teks: this.currentSoal.opsi[0].teks },
                    { teks: this.currentSoal.opsi[1].teks },
                    { teks: this.currentSoal.opsi[2].teks },
                    { teks: this.currentSoal.opsi[3].teks }
                ],
                jawaban: this.currentSoal.jawaban,
                penjelasan: this.currentSoal.penjelasan
            });
            
            this.resetForm();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        hapusSoal(index) {
            if (confirm('Yakin ingin hapus soal ini?')) {
                this.soalList.splice(index, 1);
            }
        },
        
        resetForm() {
            this.currentSoal = {
                pertanyaan: '',
                gambarSoal: null,
                opsi: [
                    { teks: '', gambar: null },
                    { teks: '', gambar: null },
                    { teks: '', gambar: null },
                    { teks: '', gambar: null }
                ],
                jawaban: 'A',
                penjelasan: ''
            };
            this.errors = {};
            
            // Clear preview images
            document.querySelectorAll('[id^="preview"]').forEach(img => {
                img.classList.add('hidden');
                img.src = '';
            });
        },
        
        previewImage(event, previewId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.getElementById(previewId);
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

function submitBankSoal() {
    const app = Alpine.$data(document.querySelector('[x-data]'));
    
    if (app.soalList.length === 0) {
        alert('❌ Minimal 1 soal harus ditambahkan!');
        return;
    }
    
    const container = document.getElementById('soalInputsContainer');
    container.innerHTML = '';
    
    app.soalList.forEach((soal, index) => {
        addHiddenInput(container, `soal_list[${index}][pertanyaan]`, soal.pertanyaan);
        addHiddenInput(container, `soal_list[${index}][jawaban]`, soal.jawaban);
        addHiddenInput(container, `soal_list[${index}][penjelasan]`, soal.penjelasan || '');
        
        soal.opsi.forEach((opsi, opsiIndex) => {
            addHiddenInput(container, `soal_list[${index}][opsi][${String.fromCharCode(65 + opsiIndex)}]`, opsi.teks);
        });
    });
    
    document.querySelector('form').submit();
}

function addHiddenInput(container, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    container.appendChild(input);
}
</script>
@endsection