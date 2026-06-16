@extends('layouts.app')

@section('title', 'Kerjakan Ujian - ' . $ujian->judul)

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="ujianKerjakan({{ json_encode($hasil->id) }}, {{ $ujian->durasi_menit }}, {{ $ujian->mode === 'ujian' ? 'true' : 'false' }}, {{ $ujian->id }})">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $ujian->judul }}</h1>
                <p class="text-blue-100">{{ $ujian->mapel->nama ?? '-' }} • {{ $soalList->count() }} Soal</p>
            </div>
            <div x-show="modeUjian" class="text-center">
                <div class="text-4xl font-bold font-mono" x-text="formatTime(waktuTersisa)"></div>
                <p class="text-sm text-blue-100">Waktu Tersisa</p>
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-2xl p-4">
        <p class="text-yellow-800 font-semibold">
            <i class="fas fa-info-circle mr-2"></i>
            @if($ujian->mode === 'ujian')
                Mode Ujian - Waktu terbatas {{ $ujian->durasi_menit }} menit
            @else
                Mode Latihan - Tidak ada batas waktu
            @endif
        </p>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-bold text-gray-700">Progress Pengerjaan</span>
            <span class="text-sm font-bold text-blue-600" x-text="soalDijawab + ' / ' + totalSoal + ' soal'"></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" 
                 :style="'width: ' + (totalSoal > 0 ? (soalDijawab / totalSoal) * 100 : 0) + '%'"></div>
        </div>
    </div>

    <!-- Soal -->
    <div class="space-y-6">
        @foreach($soalList as $index => $soalUjian)
            @php 
                $soal = $soalUjian->soal; 
                $soalId = $soal->id;
                $jawabanSiswa = is_array($hasil->jawaban) ? ($hasil->jawaban[$soalId] ?? '') : '';
            @endphp
            <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-200" 
                 x-data="{ 
                     jawaban: '{{ $jawabanSiswa }}',
                     soalId: {{ $soalId }}
                 }"
                 x-init="if(jawaban) tambahKeDijawab(soalId)">
                
                <!-- Nomor Soal -->
                <div class="flex items-center gap-3 mb-4 flex-wrap">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                        {{ $index + 1 }}
                    </span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $soal->tipe === 'pg' ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-purple-100 text-purple-700 border border-purple-300' }}">
                        {{ $soal->tipe === 'pg' ? 'PILIHAN GANDA' : 'ESSAY' }}
                    </span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $soal->level === 'mudah' ? 'bg-green-100 text-green-700' : ($soal->level === 'sulit' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($soal->level) }}
                    </span>
                    <span class="text-sm text-gray-500">Bobot: {{ $soalUjian->bobot ?? 1 }}</span>
                </div>

                <!-- Pertanyaan -->
                <p class="text-lg font-bold text-gray-900 mb-4">{{ $soal->pertanyaan }}</p>

                @if($soal->tipe === 'pg')
                    <!-- Pilihan Ganda -->
                    <div class="space-y-3">
                        @foreach(['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d] as $label => $opsi)
                            @if($opsi)
                                <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-50"
                                       :class="jawaban === '{{ $label }}' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                    <input type="radio" 
                                           name="jawaban[{{ $soalId }}]" 
                                           value="{{ $label }}"
                                           x-model="jawaban"
                                           @change="simpanJawaban({{ $soalId }}, jawaban)"
                                           class="w-5 h-5 text-blue-600">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                          :class="jawaban === '{{ $label }}' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'">
                                        {{ $label }}
                                    </span>
                                    <span class="flex-1">{{ $opsi }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @else
                    <!-- Essay -->
                    <textarea x-model="jawaban"
                              @input="simpanJawaban({{ $soalId }}, jawaban)"
                              rows="5"
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Tulis jawaban Anda di sini..."></textarea>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Tombol Submit -->
    <div class="sticky bottom-4 flex gap-3 bg-white p-4 rounded-2xl shadow-xl border-2 border-gray-200">
        <button @click="konfirmasiSubmit()" 
                class="flex-1 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 font-bold text-lg shadow-xl transition-all transform hover:scale-105">
            <i class="fas fa-paper-plane mr-2"></i> Submit Ujian
        </button>
        <a href="{{ route('siswa.ujian.daftar') }}" 
           class="px-8 py-4 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold text-lg transition-all flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Modal Konfirmasi -->
    <div x-show="showModal" 
         x-transition
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
         style="display: none;">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Yakin ingin submit?</h3>
            </div>
            <p class="text-gray-600 mb-4 text-center">Pastikan semua jawaban sudah diisi dengan benar.</p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-6">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Progress:</strong> <span x-text="soalDijawab"></span> dari <span x-text="totalSoal"></span> soal sudah dijawab
                </p>
            </div>
            
            <div class="flex gap-3">
                <button @click="showModal = false" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 font-bold">
                    Batal
                </button>
                <button @click="submitUjian()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold">
                    <i class="fas fa-check mr-1"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function ujianKerjakan(hasilId, durasiMenit, modeUjian, ujianId) {
    return {
        hasilId: hasilId,
        ujianId: ujianId,
        modeUjian: modeUjian,
        waktuTersisa: durasiMenit * 60,
        showModal: false,
        timer: null,
        totalSoal: {{ $soalList->count() }},
        soalDijawab: 0,
        soalSudahDihitung: [],
        
        init() {
            if (this.modeUjian && this.waktuTersisa > 0) {
                this.startTimer();
            }
        },
        
        tambahKeDijawab(soalId) {
            if (!this.soalSudahDihitung.includes(soalId)) {
                this.soalSudahDihitung.push(soalId);
                this.soalDijawab = this.soalSudahDihitung.length;
            }
        },
        
        startTimer() {
            this.timer = setInterval(() => {
                if (this.waktuTersisa > 0) {
                    this.waktuTersisa--;
                } else {
                    clearInterval(this.timer);
                    alert('⏰ Waktu habis! Ujian akan otomatis disubmit.');
                    this.submitUjian();
                }
            }, 1000);
        },
        
        formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            if (h > 0) {
                return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },
        
        simpanJawaban(soalId, jawaban) {
            // Tambah ke counter soal dijawab
            this.tambahKeDijawab(soalId);
            
            // Kirim via AJAX ke route simpan
            fetch(`/siswa/ujian/${this.ujianId}/simpan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    jawaban: {
                        [soalId]: jawaban
                    }
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Jawaban tersimpan:', data);
            })
            .catch(error => {
                console.error('Error menyimpan jawaban:', error);
            });
        },
        
        konfirmasiSubmit() {
            this.showModal = true;
        },
        
        submitUjian() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/siswa/ujian/${this.ujianId}/submit`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    }
}
</script>
@endsection