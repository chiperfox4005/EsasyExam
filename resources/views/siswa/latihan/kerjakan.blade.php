@extends('layouts.app')

@section('title', 'Kerjakan Latihan - ' . $paket->judul)

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="latihanKerjakan()">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📝 {{ $paket->judul }}</h1>
                <p class="text-emerald-100">{{ $soalList->count() }} soal • Mode Latihan</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold" x-text="soalDijawab + ' / ' + {{ $soalList->count() }}"></div>
                <p class="text-sm text-emerald-100">Soal Dijawab</p>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-3 rounded-full transition-all duration-300" 
                 :style="'width: ' + ({{ $soalList->count() }} > 0 ? (soalDijawab / {{ $soalList->count() }}) * 100 : 0) + '%'"></div>
        </div>
    </div>

    <!-- ✅ FORM SUBMIT - METHOD POST + CSRF -->
    <form action="{{ route('siswa.latihan.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="paket_id" value="{{ $paket->id }}">
        
        <!-- Soal List -->
        <div class="space-y-6">
            @foreach($soalList as $index => $soal)
                <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-200"
                     x-data="{ 
                         jawaban: '',
                         soalId: {{ $soal->id }}
                     }"
                     x-init="
                         $watch('jawaban', value => {
                             if (value) {
                                 tambahDijawab(soalId);
                             } else {
                                 hapusDariDijawab(soalId);
                             }
                         })
                     ">
                    
                    <!-- Nomor Soal -->
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <span class="w-10 h-10 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold">
                            {{ $index + 1 }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $soal->tipe === 'pg' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ $soal->tipe === 'pg' ? 'PILIHAN GANDA' : 'ESSAY' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $soal->level === 'mudah' ? 'bg-green-100 text-green-700' : ($soal->level === 'sulit' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($soal->level) }}
                        </span>
                    </div>

                    <!-- Pertanyaan -->
                    <p class="text-lg font-bold text-gray-900 mb-4">{{ $soal->pertanyaan }}</p>

                    @if($soal->tipe === 'pg')
                        <!-- Pilihan Ganda -->
                        <div class="space-y-3">
                            @foreach(['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d] as $label => $opsi)
                                @if($opsi)
                                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-50"
                                           :class="jawaban === '{{ $label }}' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200'">
                                        <input type="radio" 
                                               name="jawaban[{{ $soal->id }}]" 
                                               value="{{ $label }}"
                                               x-model="jawaban"
                                               class="w-5 h-5 text-emerald-600">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                              :class="jawaban === '{{ $label }}' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700'">
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
                                  name="jawaban[{{ $soal->id }}]"
                                  rows="5"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-emerald-500"
                                  placeholder="Tulis jawaban Anda di sini..."></textarea>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <div class="sticky bottom-4 flex gap-3 bg-white p-4 rounded-2xl shadow-xl border-2 border-gray-200 mt-6">
            <button type="submit" 
                    class="flex-1 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 font-bold text-lg shadow-xl transition-all transform hover:scale-105">
                <i class="fas fa-check-circle mr-2"></i> Selesai & Lihat Hasil
            </button>
            <a href="{{ route('siswa.latihan.index') }}" 
               class="px-8 py-4 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold text-lg transition-all flex items-center">
                <i class="fas fa-times mr-2"></i> Batal
            </a>
        </div>
    </form>

</div>

<script>
function latihanKerjakan() {
    return {
        soalDijawab: 0,
        soalSudahDihitung: [],
        
        tambahDijawab(soalId) {
            if (!this.soalSudahDihitung.includes(soalId)) {
                this.soalSudahDihitung.push(soalId);
                this.soalDijawab = this.soalSudahDihitung.length;
            }
        },
        
        hapusDariDijawab(soalId) {
            const index = this.soalSudahDihitung.indexOf(soalId);
            if (index > -1) {
                this.soalSudahDihitung.splice(index, 1);
                this.soalDijawab = this.soalSudahDihitung.length;
            }
        }
    }
}
</script>
@endsection