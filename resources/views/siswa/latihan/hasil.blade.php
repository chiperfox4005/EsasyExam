@extends('layouts.app')

@section('title', 'Hasil Latihan - ' . $paket->judul)

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fadeIn">
    
    <div class="bg-gradient-to-r {{ $nilai >= 75 ? 'from-green-500 to-emerald-600' : 'from-orange-500 to-red-600' }} rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $nilai >= 75 ? '🎉 Latihan Selesai!' : '💪 Terus Berlatih!' }}</h1>
                <p class="text-white text-opacity-90">{{ $paket->judul }}</p>
                <p class="text-white text-opacity-90 text-sm mt-1">{{ $paket->mapel->nama ?? '-' }}</p>
            </div>
            <div class="text-center">
                <div class="text-5xl font-bold">{{ number_format($nilai, 0) }}</div>
                <p class="text-sm text-white text-opacity-90 mt-1">Nilai Kamu</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSoal }}</p>
                    <p class="text-sm text-gray-600">Total Soal</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-green-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $benar }}</p>
                    <p class="text-sm text-gray-600">Benar</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-red-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $salah }}</p>
                    <p class="text-sm text-gray-600">Salah</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-minus-circle text-2xl text-gray-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-600">{{ $kosong }}</p>
                    <p class="text-sm text-gray-600">Kosong/Essay</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-list-check text-blue-600"></i>
            Review Jawaban
        </h3>
        
        <div class="space-y-4">
            @foreach($detailHasil as $index => $detail)
                @php 
                    $soal = $detail['soal'];
                    $jawabanSiswa = $detail['jawaban_siswa'];
                    $isCorrect = $detail['is_correct'];
                @endphp
                <div class="border-2 rounded-2xl p-4 {{ $isCorrect === true ? 'border-green-300 bg-green-50' : ($isCorrect === false ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-gray-50') }}">
                    <div class="flex items-start gap-3 mb-3">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white {{ $isCorrect === true ? 'bg-green-600' : ($isCorrect === false ? 'bg-red-600' : 'bg-gray-500') }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 mb-2">{{ $soal->pertanyaan }}</p>
                            
                            @if($soal->tipe === 'pg')
                                <div class="space-y-2">
                                    @foreach(['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d] as $label => $opsi)
                                        @if($opsi)
                                            <div class="flex items-center gap-2 p-2 rounded-lg {{ $label === $soal->jawaban ? 'bg-green-100 border-2 border-green-400' : ($label === $jawabanSiswa ? 'bg-red-100 border-2 border-red-400' : 'bg-white border border-gray-200') }}">
                                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $label === $soal->jawaban ? 'bg-green-600 text-white' : ($label === $jawabanSiswa ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700') }}">
                                                    {{ $label }}
                                                </span>
                                                <span class="flex-1 text-sm">{{ $opsi }}</span>
                                                @if($label === $soal->jawaban)
                                                    <i class="fas fa-check text-green-600"></i>
                                                @elseif($label === $jawabanSiswa)
                                                    <i class="fas fa-times text-red-600"></i>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                
                                <div class="mt-2 text-sm">
                                    <span class="font-semibold">Jawaban Anda:</span>
                                    <span class="{{ $isCorrect ? 'text-green-700' : 'text-red-700' }} font-bold">
                                        {{ $jawabanSiswa ?? 'Tidak dijawab' }}
                                    </span>
                                    @if(!$isCorrect && $soal->jawaban)
                                        <span class="text-green-700 font-bold ml-2">
                                            (Jawaban benar: {{ $soal->jawaban }})
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="mt-2">
                                    <p class="text-sm font-semibold text-gray-700">Jawaban Anda:</p>
                                    <p class="text-sm text-gray-900 mt-1 p-3 bg-white rounded-lg border border-gray-200">
                                        {{ $jawabanSiswa ?? 'Tidak dijawab' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('siswa.latihan.histori', $paket->id) }}" 
           class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 font-bold text-center transition-all shadow-lg shadow-purple-500/30">
            <i class="fas fa-history mr-2"></i> Lihat Histori Pengerjaan
        </a>
        <a href="{{ route('siswa.latihan.kerjakan', $paket->id) }}" 
           class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold text-center transition-all shadow-lg shadow-blue-500/30">
            <i class="fas fa-redo mr-2"></i> Latihan Lagi
        </a>
        <a href="{{ route('siswa.latihan.index') }}" 
           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold transition-all">
            <i class="fas fa-list mr-2"></i> Daftar Paket
        </a>
    </div>

</div>
@endsection