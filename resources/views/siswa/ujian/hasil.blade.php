@extends('layouts.app')

@section('title', 'Hasil Ujian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header: Ujian Selesai -->
    <div class="bg-white rounded-3xl p-8 text-center shadow-xl border-2 border-gray-200">
        <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-500/30">
            <i class="fas fa-check text-4xl text-white"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Ujian Selesai! 🎉</h1>
        <p class="text-gray-700 mb-6 font-medium">{{ $ujian->judul }}</p>
        
        @if($ujian->tampilkan_nilai || $ujian->mode === 'latihan')
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-2xl border-2 border-green-200">
                    <i class="fas fa-check-circle text-2xl text-green-600 mb-2"></i>
                    <p class="text-sm text-green-700 font-semibold">Benar</p>
                    <p class="text-3xl font-bold text-green-800">{{ $hasil->benar }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-5 rounded-2xl border-2 border-red-200">
                    <i class="fas fa-times-circle text-2xl text-red-600 mb-2"></i>
                    <p class="text-sm text-red-700 font-semibold">Salah</p>
                    <p class="text-3xl font-bold text-red-800">{{ $hasil->salah }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-2xl border-2 border-blue-200">
                    <i class="fas fa-star text-2xl text-blue-600 mb-2"></i>
                    <p class="text-sm text-blue-700 font-semibold">Nilai</p>
                    <p class="text-3xl font-bold text-blue-800">{{ number_format($hasil->nilai, 1) }}</p>
                </div>
            </div>
            
            <!-- Status Kelulusan -->
            <div class="mb-6 p-4 rounded-2xl {{ $hasil->nilai >= 75 ? 'bg-green-100 border-2 border-green-300' : 'bg-red-100 border-2 border-red-300' }}">
                <p class="text-lg font-bold {{ $hasil->nilai >= 75 ? 'text-green-800' : 'text-red-800' }}">
                    @if($hasil->nilai >= 75)
                        <i class="fas fa-trophy mr-2"></i> LULUS - Selamat!
                    @else
                        <i class="fas fa-exclamation-triangle mr-2"></i> BELUM LULUS - Terus Belajar!
                    @endif
                </p>
            </div>
        @endif
        
        <a href="{{ route('siswa.ujian.daftar') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Ujian
        </a>
    </div>

    <!-- 🚨 LAPORAN AKTIVITAS ANTI-CHEAT -->
    @php
        $totalSuspicious = ($hasil->tab_switch_count ?? 0) + ($hasil->copy_count ?? 0) + ($hasil->paste_count ?? 0) + ($hasil->right_click_count ?? 0);
        $isSuspicious = $totalSuspicious >= 5;
    @endphp
    
    @if($totalSuspicious > 0)
        <div class="bg-white rounded-3xl p-6 shadow-xl border-2 {{ $isSuspicious ? 'border-red-300' : 'border-orange-200' }}">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas {{ $isSuspicious ? 'fa-exclamation-triangle text-red-600' : 'fa-chart-line text-orange-600' }}"></i>
                    Laporan Aktivitas Ujian
                </h3>
                @if($isSuspicious)
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border-2 border-red-300">
                        <i class="fas fa-flag mr-1"></i> Perlu Review
                    </span>
                @else
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border-2 border-green-300">
                        <i class="fas fa-check mr-1"></i> Normal
                    </span>
                @endif
            </div>
            
            <!-- Statistik Aktivitas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <!-- Pindah Tab -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-orange-200 rounded-2xl p-4 text-center">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md">
                        <i class="fas fa-window-restore text-xl text-white"></i>
                    </div>
                    <p class="text-3xl font-bold text-orange-800">{{ $hasil->tab_switch_count ?? 0 }}</p>
                    <p class="text-xs text-gray-700 font-semibold mt-1">Pindah Tab</p>
                </div>
                
                <!-- Copy -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-2xl p-4 text-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md">
                        <i class="fas fa-copy text-xl text-white"></i>
                    </div>
                    <p class="text-3xl font-bold text-blue-800">{{ $hasil->copy_count ?? 0 }}</p>
                    <p class="text-xs text-gray-700 font-semibold mt-1">Copy</p>
                </div>
                
                <!-- Paste -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-2xl p-4 text-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md">
                        <i class="fas fa-paste text-xl text-white"></i>
                    </div>
                    <p class="text-3xl font-bold text-purple-800">{{ $hasil->paste_count ?? 0 }}</p>
                    <p class="text-xs text-gray-700 font-semibold mt-1">Paste</p>
                </div>
                
                <!-- Klik Kanan -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-200 rounded-2xl p-4 text-center">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md">
                        <i class="fas fa-mouse-pointer text-xl text-white"></i>
                    </div>
                    <p class="text-3xl font-bold text-red-800">{{ $hasil->right_click_count ?? 0 }}</p>
                    <p class="text-xs text-gray-700 font-semibold mt-1">Klik Kanan</p>
                </div>
            </div>
            
            <!-- Total Aktivitas -->
            <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Total Aktivitas Tercatat</p>
                    <p class="text-xs text-gray-500">Semua aktivitas selama ujian berlangsung</p>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalSuspicious }}</p>
            </div>
            
            <!-- Warning jika mencurigakan -->
            @if($isSuspicious)
                <div class="mt-4 p-4 bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-300 rounded-2xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="text-sm text-red-800 font-bold mb-1">Terdeteksi Aktivitas Mencurigakan</p>
                            <p class="text-xs text-red-700">
                                Terdapat {{ $totalSuspicious }} aktivitas yang tercatat selama ujian berlangsung. 
                                Guru akan meninjau laporan ini untuk memastikan integritas ujian.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 rounded-2xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-shield-alt text-green-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="text-sm text-green-800 font-bold mb-1">Aktivitas Normal</p>
                            <p class="text-xs text-green-700">
                                Aktivitas selama ujian dalam batas wajar. Pertahankan integritas!
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- Jika tidak ada aktivitas tercatat -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-green-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shield-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Laporan Aktivitas</h3>
                    <p class="text-sm text-green-700 font-semibold">
                        <i class="fas fa-check-circle mr-1"></i> Tidak ada aktivitas mencurigakan terdeteksi. Integritas ujian terjaga!
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Review Soal (jika diizinkan) -->
    @if($soalList && ($ujian->tampilkan_nilai || $ujian->mode === 'latihan'))
        <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-gray-200">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-list-check text-blue-600"></i>
                    Review Jawaban
                </h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border-2 border-blue-300">
                    {{ $soalList->count() }} Soal
                </span>
            </div>
            
            <div class="space-y-4">
                @foreach($soalList as $index => $item)
                    @php
                        $soal = $item->soal;
                        $jawabanSiswa = $hasil->jawaban[$soal->id] ?? null;
                        $isCorrect = $soal->tipe === 'pg' ? ($jawabanSiswa === $soal->jawaban) : null;
                    @endphp
                    
                    <div class="border-2 rounded-2xl p-5 transition-all {{ 
                        $soal->tipe === 'pg' 
                            ? ($isCorrect ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50') 
                            : 'border-gray-200 bg-gray-50' 
                    }}">
                        <!-- Header Soal -->
                        <div class="flex items-start gap-3 mb-3">
                            <!-- Nomor Soal -->
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shadow-md {{ 
                                $soal->tipe === 'pg' 
                                    ? ($isCorrect ? 'bg-green-500' : 'bg-red-500') 
                                    : 'bg-gray-500' 
                            }}">
                                {{ $index + 1 }}
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full {{ $soal->tipe === 'pg' ? 'bg-blue-100 text-blue-700 border-2 border-blue-300' : 'bg-purple-100 text-purple-700 border-2 border-purple-300' }}">
                                        {{ strtoupper($soal->tipe) }}
                                    </span>
                                    @if($soal->tipe === 'pg')
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $isCorrect ? 'bg-green-200 text-green-800 border-2 border-green-300' : 'bg-red-200 text-red-800 border-2 border-red-300' }}">
                                            {{ $isCorrect ? '✓ Benar' : '✗ Salah' }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border-2 border-yellow-300">
                                            ⏳ Perlu Koreksi Manual
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-900 font-semibold">{{ $soal->pertanyaan }}</p>
                            </div>
                        </div>
                        
                        <!-- Gambar Soal (jika ada) -->
                        @if($soal->gambar_soal)
                            <img src="{{ asset('storage/' . $soal->gambar_soal) }}" class="max-h-64 rounded-xl border-2 border-gray-200 mb-3 ml-13">
                        @endif
                        
                        <!-- Jawaban PG -->
                        @if($soal->tipe === 'pg')
                            <div class="ml-13 space-y-2 mt-3">
                                @foreach(['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d', 'E' => 'opsi_e'] as $label => $key)
                                    @if($soal->$key || $soal->{"${key}_gambar"})
                                        @php
                                            $tipe = $soal->{"${key}_tipe"} ?? 'text';
                                            $isJawabanSiswa = $jawabanSiswa === $label;
                                            $isJawabanBenar = $soal->jawaban === $label;
                                        @endphp
                                        
                                        <div class="flex items-start gap-3 p-3 rounded-xl border-2 {{ 
                                            $isJawabanBenar ? 'border-green-400 bg-green-100' : 
                                            ($isJawabanSiswa ? 'border-red-400 bg-red-100' : 'border-gray-200 bg-white')
                                        }}">
                                            <span class="w-7 h-7 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0 {{ 
                                                $isJawabanBenar ? 'bg-green-600 text-white' : 
                                                ($isJawabanSiswa ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700')
                                            }}">
                                                {{ $label }}
                                            </span>
                                            <div class="flex-1">
                                                @if($tipe !== 'image' && $soal->$key)
                                                    <p class="text-sm text-gray-800 font-medium">{{ $soal->$key }}</p>
                                                @endif
                                                @if($tipe !== 'text' && $soal->{"${key}_gambar"})
                                                    <img src="{{ asset('storage/' . $soal->{"${key}_gambar"}) }}" class="max-h-32 rounded-lg border border-gray-200 mt-1">
                                                @endif
                                            </div>
                                            @if($isJawabanBenar)
                                                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                            @elseif($isJawabanSiswa)
                                                <i class="fas fa-times-circle text-red-600 text-lg"></i>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                                
                                @if(!$jawabanSiswa)
                                    <p class="text-sm text-gray-600 italic mt-2">
                                        <i class="fas fa-minus-circle mr-1"></i> Tidak dijawab
                                    </p>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Jawaban Essay -->
                        @if($soal->tipe === 'essay')
                            <div class="ml-13 mt-3">
                                <p class="text-xs font-bold text-gray-700 mb-2">Jawaban Kamu:</p>
                                <div class="bg-white p-4 rounded-xl border-2 border-gray-200">
                                    @if($jawabanSiswa)
                                        <p class="text-sm text-gray-900">{{ $jawabanSiswa }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Tidak dijawab</p>
                                    @endif
                                    
                                    {{-- Tampilkan gambar jawaban essay jika ada --}}
                                    @if(isset($hasil->jawaban_gambar[$soal->id]) && $hasil->jawaban_gambar[$soal->id])
                                        <img src="{{ asset('storage/' . $hasil->jawaban_gambar[$soal->id]) }}" class="max-h-48 rounded-lg border border-gray-200 mt-3">
                                    @endif
                                </div>
                                
                                {{-- Kunci Jawaban (untuk mode latihan) --}}
                                @if($ujian->mode === 'latihan' && $soal->jawaban)
                                    <div class="mt-3 p-3 bg-purple-50 border-2 border-purple-200 rounded-xl">
                                        <p class="text-xs font-bold text-purple-700 mb-1">
                                            <i class="fas fa-lightbulb mr-1"></i> Kunci Jawaban:
                                        </p>
                                        <p class="text-sm text-gray-800">{{ $soal->jawaban }}</p>
                                        @if($soal->jawaban_gambar)
                                            <img src="{{ asset('storage/' . $soal->jawaban_gambar) }}" class="max-h-40 rounded-lg mt-2 border-2 border-purple-200">
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection