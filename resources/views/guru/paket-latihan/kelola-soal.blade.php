@extends('layouts.app')

@section('title', 'Kelola Soal - ' . $paket->judul)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2"> Kelola Soal Paket</h1>
                <p class="text-purple-100">{{ $paket->judul }}</p>
                <p class="text-purple-100 text-sm mt-1">{{ $paket->mapel->nama }} • {{ $soalPaketList->count() }} soal</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('guru.paket-latihan.edit', $paket->id) }}" 
                   class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-xl hover:bg-opacity-30 font-bold">
                    <i class="fas fa-edit mr-2"></i> Edit Info
                </a>
                <a href="{{ route('guru.paket-latihan.index') }}" 
                   class="px-4 py-2 bg-white text-purple-600 rounded-xl hover:shadow-lg font-bold">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-2 border-green-300 rounded-2xl p-4">
            <p class="text-green-800 font-semibold"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-2 border-red-300 rounded-2xl p-4">
            <p class="text-red-800 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Soal dalam Paket (2/3) -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-list-check text-blue-600"></i>
                    Soal dalam Paket ({{ $soalPaketList->count() }})
                </h3>
            </div>
            
            <div class="p-6 max-h-[600px] overflow-y-auto">
                @if($soalPaketList->count() > 0)
                    <div class="space-y-3">
                        @foreach($soalPaketList as $index => $soal)
                            <div class="border-2 border-gray-200 rounded-2xl p-4 hover:border-purple-400 transition-all">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $soal->tipe === 'pg' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ $soal->tipe === 'pg' ? 'PG' : 'ESSAY' }}
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700">
                                                {{ ucfirst($soal->level) }}
                                            </span>
                                        </div>
                                        <p class="text-gray-900 font-semibold mb-2">{{ $soal->pertanyaan }}</p>
                                        @if($soal->tipe === 'pg')
                                            <div class="text-xs text-gray-600 space-y-1">
                                                @foreach(['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d] as $label => $opsi)
                                                    @if($opsi)
                                                        <div class="{{ $label === $soal->jawaban ? 'text-green-700 font-bold' : '' }}">
                                                            {{ $label }}. {{ $opsi }} {{ $label === $soal->jawaban ? '✓' : '' }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('guru.paket-latihan.hapus-soal', [$paket->id, $soal->id]) }}" method="POST" 
                                          onsubmit="return confirm('Yakin hapus soal ini dari paket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-bold">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-600 font-semibold">Belum ada soal dalam paket ini</p>
                        <p class="text-sm text-gray-500 mt-1">Tambahkan soal dari bank soal di sebelah kanan →</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tambah Soal dari Bank (1/3) -->
        <div class="bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-green-600"></i>
                    Tambah Soal
                </h3>
                <p class="text-xs text-gray-600 mt-1">Pilih soal dari Bank Soal mapel {{ $paket->mapel->nama }}</p>
            </div>
            
            <div class="p-4 max-h-[600px] overflow-y-auto">
                @if($bankSoalList->count() > 0)
                    <div class="space-y-2">
                        @foreach($bankSoalList as $bankSoal)
                            <div class="border-2 border-gray-200 rounded-xl p-3 hover:border-green-400 transition-all">
                                <div class="flex items-start gap-2 mb-2">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $bankSoal->tipe === 'pg' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $bankSoal->tipe === 'pg' ? 'PG' : 'ESSAY' }}
                                    </span>
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700">
                                        {{ ucfirst($bankSoal->level) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-900 font-medium mb-3 line-clamp-2">{{ $bankSoal->pertanyaan }}</p>
                                <form action="{{ route('guru.paket-latihan.tambah-soal', $paket->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="bank_soal_id" value="{{ $bankSoal->id }}">
                                    <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-bold">
                                        <i class="fas fa-plus mr-1"></i> Tambahkan
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 font-semibold">Tidak ada soal tersedia</p>
                        <p class="text-xs text-gray-500 mt-1">Buat soal baru di Bank Soal untuk mapel ini</p>
                        <a href="{{ route('bank-soal.create') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Buat Soal Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection