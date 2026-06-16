<div class="min-h-screen bg-gray-50" 
     x-data="examTimer({{ $waktuSisa }}, '{{ $ujian->mode }}', {
         deteksi_tab_switch: {{ $ujian->deteksi_tab_switch ? 'true' : 'false' }},
         boleh_copy_paste: {{ $ujian->boleh_copy_paste ? 'true' : 'false' }},
         max_tab_switch: {{ $ujian->max_tab_switch }},
         tampilkan_nilai: {{ $ujian->tampilkan_nilai ? 'true' : 'false' }}
     })" 
     x-init="startTimer()">
    
    <!-- ==================== HALAMAN SELESAI ==================== -->
    @if($isFinished)
        <div class="max-w-4xl mx-auto p-6 mt-10 animate-fadeIn">
            <div class="bg-white rounded-3xl p-8 text-center shadow-lg">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-4xl text-green-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ $ujian->mode === 'latihan' ? 'Latihan Selesai! 🎉' : 'Ujian Selesai! 🎉' }}
                </h2>
                <p class="text-gray-600 mb-6">
                    {{ $ujian->mode === 'latihan' ? 'Semoga latihan ini bermanfaat untuk belajarmu.' : 'Terima kasih telah mengerjakan ujian ini.' }}
                </p>
                
                @if($ujian->tampilkan_nilai)
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-green-50 p-4 rounded-2xl">
                            <p class="text-sm text-green-600">Benar</p>
                            <p class="text-2xl font-bold text-green-700">{{ $hasil->benar ?? 0 }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-2xl">
                            <p class="text-sm text-red-600">Salah</p>
                            <p class="text-2xl font-bold text-red-700">{{ $hasil->salah ?? 0 }}</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-2xl">
                            <p class="text-sm text-blue-600">Nilai</p>
                            <p class="text-2xl font-bold text-blue-700">{{ number_format($hasil->nilai ?? 0, 1) }}</p>
                        </div>
                    </div>
                @endif

                <!-- Laporan Pelanggaran -->
                @if($ujian->mode === 'ujian' && ($hasil->jumlah_pelanggaran ?? 0) > 0)
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 text-left">
                        <h4 class="font-bold text-red-900 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Laporan Pelanggaran ({{ $hasil->jumlah_pelanggaran }} kali)
                        </h4>
                        <div class="space-y-2 text-sm text-red-800">
                            @foreach($hasil->log_pelanggaran ?? [] as $log)
                                <div class="flex justify-between items-center bg-white p-2 rounded-lg">
                                    <span>{{ $log['tipe'] === 'tab_switch' ? '🔀 Pindah Tab' : ($log['tipe'] === 'copy' ? '📋 Copy' : '📋 Paste') }}</span>
                                    <span class="text-xs text-gray-500">{{ $log['waktu'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tombol Download Report -->
                <div class="flex gap-3 justify-center flex-wrap">
                    <button class="px-6 py-3 bg-green-600 text-white rounded-2xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download Laporan
                    </button>
                    <a href="{{ route('siswa.dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-semibold hover:bg-blue-700 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

    <!-- ==================== HALAMAN MENGERJAKAN ==================== -->
    @else
        <!-- Topbar Ujian -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-900">{{ $ujian->judul }}</h2>
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        {{ $ujian->mapel->nama }} • {{ $soalList->count() }} Soal
                        <span class="px-2 py-1 rounded-full text-xs {{ $ujian->mode === 'latihan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($ujian->mode) }}
                        </span>
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Indikator Pelanggaran -->
                    @if($ujian->mode === 'ujian' && $ujian->deteksi_tab_switch)
                        <div class="hidden md:flex items-center gap-2 px-3 py-2 bg-orange-50 text-orange-600 rounded-2xl text-sm">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span x-text="violations + '/{{ $ujian->max_tab_switch }}'"></span>
                        </div>
                    @endif

                    <!-- Timer (Hanya untuk Mode Ujian) -->
                    @if($ujian->mode === 'ujian')
                        <div class="bg-red-50 text-red-600 px-4 py-2 rounded-2xl font-mono font-bold text-lg flex items-center gap-2">
                            <i class="far fa-clock"></i>
                            <span x-text="formatTime(timeLeft)"></span>
                        </div>
                    @else
                        <div class="bg-green-50 text-green-600 px-4 py-2 rounded-2xl font-medium text-sm flex items-center gap-2">
                            <i class="fas fa-infinity"></i>
                            <span>Tanpa Batas Waktu</span>
                        </div>
                    @endif

                    <button @click="confirmSubmit()" class="px-4 py-2 bg-blue-600 text-white rounded-2xl font-semibold hover:bg-blue-700 transition-colors">
                        Kumpulkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Area Soal -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <!-- Header Soal -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-bold">
                            Soal No. {{ $currentIndex + 1 }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 rounded-full text-xs {{ $soalList[$currentIndex]->soal->level == 'mudah' ? 'bg-green-100 text-green-700' : ($soalList[$currentIndex]->soal->level == 'sedang' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($soalList[$currentIndex]->soal->level) }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ strtoupper($soalList[$currentIndex]->soal->tipe) }}
                            </span>
                        </div>
                    </div>

                    <!-- Pertanyaan -->
                    <div class="mb-6">
                        <p class="text-lg text-gray-900 font-medium leading-relaxed">
                            {{ $soalList[$currentIndex]->soal->pertanyaan }}
                        </p>
                        @if($soalList[$currentIndex]->soal->gambar_soal)
                            <img src="{{ asset('storage/' . $soalList[$currentIndex]->soal->gambar_soal) }}" class="mt-4 rounded-2xl max-h-64 object-contain">
                        @endif
                    </div>

                    <!-- ==================== SOAL PILIHAN GANDA ==================== -->
                    @if($soalList[$currentIndex]->soal->tipe === 'pg')
                        <div class="space-y-3">
                            @php
                                $soal = $soalList[$currentIndex]->soal;
                                $opsi = ['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d, 'E' => $soal->opsi_e];
                                $userAnswer = $jawaban[$soal->id] ?? null;
                            @endphp
                            
                            @foreach($opsi as $key => $val)
                                @if($val)
                                    @php
                                        $borderClass = 'border-gray-200 hover:bg-blue-50 hover:border-blue-300';
                                        $iconHtml = '';
                                        $isDisabled = false;
                                        
                                        // Mode latihan: tampilkan feedback warna
                                        if ($ujian->mode === 'latihan' && $userAnswer) {
                                            $isDisabled = true;
                                            if ($key === $soal->jawaban) {
                                                $borderClass = 'border-green-500 bg-green-50';
                                                $iconHtml = '<i class="fas fa-check-circle text-green-600 ml-auto text-lg"></i>';
                                            } elseif ($key === $userAnswer && $key !== $soal->jawaban) {
                                                $borderClass = 'border-red-500 bg-red-50';
                                                $iconHtml = '<i class="fas fa-times-circle text-red-600 ml-auto text-lg"></i>';
                                            }
                                        } elseif ($userAnswer === $key) {
                                            $borderClass = 'border-blue-500 bg-blue-50';
                                        }
                                    @endphp
                                    
                                    <label class="flex items-center gap-3 p-4 border-2 {{ $borderClass }} rounded-2xl cursor-pointer transition-all duration-200 {{ $isDisabled ? 'cursor-default' : '' }}">
                                        <input 
                                            type="radio" 
                                            wire:click="saveJawaban({{ $soal->id }}, '{{ $key }}')" 
                                            name="opsi_{{ $soal->id }}" 
                                            value="{{ $key }}" 
                                            class="mt-0.5 text-blue-600 focus:ring-blue-500" 
                                            {{ $userAnswer == $key ? 'checked' : '' }}
                                            {{ $isDisabled ? 'disabled' : '' }}
                                        >
                                        <div class="flex-1 flex items-center">
                                            <span class="font-bold text-gray-700 mr-2">{{ $key }}.</span>
                                            <span class="text-gray-900">{{ $val }}</span>
                                            {!! $iconHtml !!}
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                            
                            <!-- Feedback Jawaban Benar (Mode Latihan) -->
                            @if($ujian->mode === 'latihan' && $userAnswer)
                                <div class="mt-4 p-4 bg-blue-50 rounded-2xl border border-blue-200">
                                    <p class="text-sm text-blue-900 mb-2">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Jawaban Benar:</strong> {{ $soal->jawaban }}. {{ $opsi[$soal->jawaban] }}
                                    </p>
                                    @if($userAnswer === $soal->jawaban)
                                        <div class="flex items-center gap-2 text-green-700 text-sm">
                                            <i class="fas fa-trophy"></i>
                                            <span>Hebat! Jawaban kamu benar!</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-red-700 text-sm">
                                            <i class="fas fa-lightbulb"></i>
                                            <span>Pelajari kembali materi ini ya!</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    <!-- ==================== SOAL ESSAY ==================== -->
                    @elseif($soalList[$currentIndex]->soal->tipe === 'essay')
                        <textarea 
                            wire:change="saveJawaban({{ $soalList[$currentIndex]->soal->id }}, $event.target.value)" 
                            rows="8" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none" 
                            placeholder="Tulis jawaban Anda di sini...">{{ $jawaban[$soalList[$currentIndex]->soal->id] ?? '' }}</textarea>
                        
                        @if($ujian->mode === 'latihan')
                            <div class="mt-4 p-4 bg-yellow-50 rounded-2xl border border-yellow-200">
                                <p class="text-sm text-yellow-900">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    <strong>Panduan Jawaban:</strong> {{ $soalList[$currentIndex]->soal->jawaban }}
                                </p>
                            </div>
                        @endif
                    @endif

                    <!-- Navigasi Soal -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-100">
                        <button 
                            wire:click="$set('currentIndex', {{ $currentIndex - 1 }})" 
                            {{ $currentIndex == 0 ? 'disabled' : '' }} 
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                        </button>
                        
                        @if($currentIndex == $soalList->count() - 1)
                            <button @click="confirmSubmit()" class="px-6 py-2 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i> Selesai & Kumpulkan
                            </button>
                        @else
                            <button 
                                wire:click="$set('currentIndex', {{ $currentIndex + 1 }})" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors"
                            >
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigasi Nomor -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">Navigasi Soal</h3>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($soalList as $index => $item)
                            @php
                                $isAnswered = isset($jawaban[$item->soal_id]);
                                $isActive = $index == $currentIndex;
                            @endphp
                            <button 
                                wire:click="$set('currentIndex', {{ $index }})" 
                                class="w-10 h-10 rounded-xl text-sm font-bold transition-all duration-200 
                                    {{ $isActive ? 'bg-blue-600 text-white shadow-lg scale-110' : ($isAnswered ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200') }}"
                            >
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                    
                    <!-- Legend -->
                    <div class="mt-6 space-y-2 text-xs text-gray-600 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-blue-600 rounded"></div>
                            <span>Soal Aktif</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-100 rounded"></div>
                            <span>Sudah Dijawab</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-100 rounded"></div>
                            <span>Belum Dijawab</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        @php
                            $answered = count($jawaban);
                            $total = $soalList->count();
                            $progress = $total > 0 ? ($answered / $total) * 100 : 0;
                        @endphp
                        <div class="flex justify-between text-xs text-gray-600 mb-2">
                            <span>Progress</span>
                            <span>{{ $answered }}/{{ $total }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== MODAL KONFIRMASI SUBMIT ==================== -->
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-question text-3xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kumpulkan {{ ucfirst($ujian->mode) }}?</h3>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-4 mb-6 text-sm">
                    <p class="text-gray-700 mb-3">Pastikan kamu sudah yakin dengan jawabanmu:</p>
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span>Total Soal:</span>
                            <strong>{{ $soalList->count() }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Sudah Dijawab:</span>
                            <strong class="text-green-600">{{ count($jawaban) }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Belum Dijawab:</span>
                            <strong class="text-red-600">{{ $soalList->count() - count($jawaban) }}</strong>
                        </div>
                        @if($ujian->mode === 'ujian' && $jumlahPelanggaran > 0)
                            <div class="flex justify-between pt-2 border-t border-gray-200 mt-2">
                                <span>Pelanggaran:</span>
                                <strong class="text-orange-600">{{ $jumlahPelanggaran }} kali</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <p class="text-gray-600 text-sm text-center mb-6">
                    @if($ujian->mode === 'ujian')
                        Kamu tidak dapat mengubah jawaban setelah dikumpulkan.
                    @else
                        Hasil latihan akan langsung ditampilkan.
                    @endif
                </p>

                <div class="flex gap-3">
                    <button @click="showModal = false" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 font-semibold transition-colors">
                        Batal
                    </button>
                    <button wire:click="submitUjian" @click="showModal = false" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-semibold transition-colors">
                        Ya, Kumpulkan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ==================== SCRIPT ALPINE.JS ==================== -->
    <script>
        function examTimer(initialTime, mode, config) {
            return {
                timeLeft: initialTime,
                showModal: false,
                mode: mode,
                config: config,
                violations: 0,
                
                startTimer() {
                    // Timer hanya aktif untuk mode ujian
                    if (this.mode === 'ujian' && this.timeLeft > 0) {
                        const interval = setInterval(() => {
                            this.timeLeft--;
                            if (this.timeLeft <= 0) {
                                clearInterval(interval);
                                alert('Waktu habis! Ujian akan otomatis dikumpulkan.');
                                @this.submitUjian();
                            }
                        }, 1000);
                    }
                    
                    // Anti-cheat: Deteksi tab switch (hanya mode ujian)
                    if (this.mode === 'ujian' && this.config.deteksi_tab_switch) {
                        document.addEventListener('visibilitychange', () => {
                            if (document.hidden) {
                                this.violations++;
                                @this.catatPelanggaran('tab_switch', 'Pindah tab/window');
                                
                                if (this.violations >= this.config.max_tab_switch) {
                                    alert('⚠️ PERINGATAN KERAS!\n\nAnda telah pindah tab ' + this.violations + ' kali!\nUjian akan otomatis dikumpulkan karena pelanggaran aturan.');
                                    @this.submitUjian();
                                } else {
                                    alert('⚠️ PERINGATAN!\n\nJangan pindah tab saat ujian!\nPelanggaran: ' + this.violations + '/' + this.config.max_tab_switch);
                                }
                            }
                        });
                    }
                    
                    // Anti-cheat: Disable copy-paste (jika tidak diizinkan)
                    if (this.mode === 'ujian' && !this.config.boleh_copy_paste) {
                        document.addEventListener('copy', (e) => {
                            e.preventDefault();
                            @this.catatPelanggaran('copy', 'Mencoba copy text');
                            alert('❌ Copy tidak diizinkan dalam ujian!');
                        });
                        
                        document.addEventListener('paste', (e) => {
                            e.preventDefault();
                            @this.catatPelanggaran('paste', 'Mencoba paste text');
                            alert('❌ Paste tidak diizinkan dalam ujian!');
                        });
                        
                        document.addEventListener('cut', (e) => {
                            e.preventDefault();
                        });
                        
                        document.addEventListener('contextmenu', (e) => {
                            e.preventDefault();
                            @this.catatPelanggaran('right_click', 'Mencoba klik kanan');
                        });
                        
                        // Disable shortcut keys
                        document.addEventListener('keydown', (e) => {
                            if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'u' || e.key === 's')) {
                                e.preventDefault();
                            }
                            if (e.key === 'F12') {
                                e.preventDefault();
                            }
                        });
                    }
                },
                
                formatTime(seconds) {
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    const s = seconds % 60;
                    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },
                
                confirmSubmit() {
                    this.showModal = true;
                }
            }
        }
    </script>

    <!-- ==================== CUSTOM STYLES ==================== -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        
        /* Disable text selection for exam mode */
        .select-none-exam {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</div>