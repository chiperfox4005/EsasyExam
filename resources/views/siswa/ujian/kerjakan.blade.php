@extends('layouts.app')

@section('title', 'Kerjakan: ' . $ujian->judul)

@section('content')
<div class="max-w-7xl mx-auto" x-data="examApp({{ $waktuSisa }}, '{{ $ujian->mode }}')">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- AREA SOAL (2/3 KIRI) -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Header Ujian -->
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-200 sticky top-20 z-30">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">{{ $ujian->judul }}</h1>
                        <p class="text-xs text-gray-600">{{ $ujian->mapel->nama }}</p>
                    </div>
                    <button @click="confirmSubmit()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold text-sm shadow-lg">
                        <i class="fas fa-paper-plane mr-1"></i> Kumpulkan
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="examForm" action="{{ route('siswa.ujian.submit', $ujian->id) }}" method="POST">
                @csrf
                
                <input type="hidden" name="tab_switch_count" :value="tracking.tabSwitch">
                <input type="hidden" name="copy_count" :value="tracking.copy">
                <input type="hidden" name="paste_count" :value="tracking.paste">
                <input type="hidden" name="right_click_count" :value="tracking.rightClick">
                <input type="hidden" name="ragu_ragu" :value="JSON.stringify(raguRagu)">

                <!-- List Soal -->
                <div class="space-y-4">
                    @foreach($soalList as $index => $item)
                        @php $soal = $item->soal; @endphp
                        
                        <div class="bg-white rounded-2xl shadow-lg border-2 transition-all overflow-hidden"
                             :class="{
                                 'border-blue-500 shadow-blue-500/20': currentSoal === {{ $index }},
                                 'border-green-400': currentSoal > {{ $index }} && jawaban[{{ $soal->id }}],
                                 'border-yellow-400': raguRagu.includes({{ $soal->id }}),
                                 'border-gray-200': currentSoal < {{ $index }}
                             }"
                             x-show="currentSoal >= {{ $index }}"
                             x-cloak>
                            
                            <!-- Header Soal -->
                            <div class="p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50" 
                                 @click="currentSoal = {{ $index }}">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-lg shadow-md"
                                     :class="{
                                         'bg-blue-500 text-white': currentSoal === {{ $index }},
                                         'bg-green-500 text-white': currentSoal > {{ $index }} && jawaban[{{ $soal->id }}] && !raguRagu.includes({{ $soal->id }}),
                                         'bg-yellow-400 text-white': raguRagu.includes({{ $soal->id }}),
                                         'bg-gray-200 text-gray-600': currentSoal < {{ $index }}
                                     }">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ Str::limit($soal->pertanyaan, 60) }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400" :class="{ 'rotate-180': currentSoal === {{ $index }} }"></i>
                            </div>
                            
                            <!-- Body Soal -->
                            <div x-show="currentSoal === {{ $index }}" x-transition class="border-t-2 border-gray-100 p-5">
                                <p class="text-gray-900 font-semibold mb-4">{{ $soal->pertanyaan }}</p>
                                
                                @if($soal->gambar_soal)
                                    <img src="{{ asset('storage/' . $soal->gambar_soal) }}" class="max-h-64 rounded-xl border-2 border-gray-200 mb-4">
                                @endif

                                @if($soal->tipe === 'pg')
                                    <div class="space-y-2">
                                        @foreach(['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d'] as $label => $key)
                                            @if($soal->$key)
                                                <label class="block cursor-pointer">
                                                    <div class="flex items-center gap-3 p-3 border-2 rounded-xl transition-all"
                                                         :class="jawaban[{{ $soal->id }}] === '{{ $label }}' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $label }}"
                                                               x-model="jawaban[{{ $soal->id }}]" class="w-5 h-5 text-blue-600">
                                                        <span class="font-bold text-gray-700">{{ $label }}.</span>
                                                        <span class="text-gray-800">{{ $soal->$key }}</span>
                                                    </div>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                @if($soal->tipe === 'essay')
                                    <textarea name="jawaban_essay[{{ $soal->id }}]" rows="5" x-model="jawaban[{{ $soal->id }}]"
                                              class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl"
                                              placeholder="Tulis jawaban..."></textarea>
                                @endif

                                <!-- Navigasi -->
                                <div class="flex justify-between mt-6 pt-4 border-t-2 border-gray-200">
                                    <button type="button" @click="currentSoal = Math.max(0, currentSoal - 1)"
                                            :disabled="currentSoal === 0"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-xl disabled:opacity-50 font-bold">
                                        <i class="fas fa-arrow-left mr-1"></i> Sebelumnya
                                    </button>
                                    
                                    <!-- Tombol Ragu-ragu -->
                                    <button type="button" @click="toggleRagu({{ $soal->id }})"
                                            class="px-4 py-2 rounded-xl font-bold transition-all"
                                            :class="raguRagu.includes({{ $soal->id }}) ? 'bg-yellow-400 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'">
                                        <i class="fas fa-question-circle mr-1"></i>
                                        <span x-text="raguRagu.includes({{ $soal->id }}) ? 'Ragu-ragu ✓' : 'Tandai Ragu'"></span>
                                    </button>
                                    
                                    @if($index == $soalList->count() - 1)
                                        <button type="button" @click="confirmSubmit()" class="px-4 py-2 bg-green-600 text-white rounded-xl font-bold">
                                            <i class="fas fa-paper-plane mr-1"></i> Kumpulkan
                                        </button>
                                    @else
                                        <button type="button" @click="currentSoal++" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold">
                                            Lanjut <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>

        <!-- SIDEBAR KANAN (1/3) -->
        <div class="space-y-4 lg:sticky lg:top-20 lg:self-start">
            
            <!-- 1. TIMER -->
            @if($ujian->mode === 'ujian')
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-5 text-white shadow-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-clock text-xl"></i>
                        <p class="font-bold">Waktu Tersisa</p>
                    </div>
                    <p class="text-4xl font-bold font-mono" x-text="formatTime(timeLeft)"></p>
                    <p class="text-xs text-red-100 mt-1" x-show="timeLeft < 300">⚠️ Waktu hampir habis!</p>
                </div>
            @endif

            <!-- 2. STATS SOAL -->
            <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-200">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-blue-600"></i>
                    Progress Soal
                </h3>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-3">
                        <p class="text-2xl font-bold text-green-600" x-text="Object.keys(jawaban).filter(k => jawaban[k]).length"></p>
                        <p class="text-xs text-green-700 font-semibold">Terjawab</p>
                    </div>
                    <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-3">
                        <p class="text-2xl font-bold text-gray-600" x-text="{{ $soalList->count() }} - Object.keys(jawaban).filter(k => jawaban[k]).length"></p>
                        <p class="text-xs text-gray-700 font-semibold">Kosong</p>
                    </div>
                    <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-3">
                        <p class="text-2xl font-bold text-yellow-600" x-text="raguRagu.length"></p>
                        <p class="text-xs text-yellow-700 font-semibold">Ragu</p>
                    </div>
                </div>
                
                <!-- Navigasi Cepat -->
                <div class="mt-4">
                    <p class="text-xs font-bold text-gray-700 mb-2">Navigasi Cepat:</p>
                    <div class="grid grid-cols-5 gap-1">
                        @foreach($soalList as $index => $item)
                            <button @click="currentSoal = {{ $index }}"
                                    class="w-8 h-8 rounded-lg text-xs font-bold transition-all"
                                    :class="{
                                        'bg-blue-500 text-white': currentSoal === {{ $index }},
                                        'bg-green-100 text-green-700 border border-green-300': currentSoal !== {{ $index }} && jawaban[{{ $item->soal_id }}] && !raguRagu.includes({{ $item->soal_id }}),
                                        'bg-yellow-100 text-yellow-700 border border-yellow-300': raguRagu.includes({{ $item->soal_id }}),
                                        'bg-gray-100 text-gray-700 border border-gray-300': !jawaban[{{ $item->soal_id }}] && !raguRagu.includes({{ $item->soal_id }})
                                    }">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 3. CHAT AI MENTOR -->
            <div class="bg-white rounded-2xl shadow-lg border-2 border-purple-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-4 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-robot text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold">Mentor AI</p>
                            <p class="text-xs text-purple-100">Teman belajar kamu 🤖</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <div id="chatMessages" class="h-64 overflow-y-auto space-y-3 mb-3">
                        <div class="flex gap-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-robot text-purple-600 text-sm"></i>
                            </div>
                            <div class="bg-purple-50 rounded-2xl rounded-tl-none p-3 max-w-xs">
                                <p class="text-sm text-gray-800">Halo! 👋 Aku Mentor AI. Aku bisa bantu kasih <strong>arahan</strong> tapi bukan jawaban ya. Yuk tanya aku kalau bingung! 😊</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <input type="text" x-model="chatInput" @keydown.enter="sendChat()"
                               placeholder="Tanya arahan..." 
                               class="flex-1 px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                        <button @click="sendChat()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    
                    <div class="mt-3 flex flex-wrap gap-1">
                        <button @click="quickChat('soal ini tentang apa?')" class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded-full hover:bg-purple-100">💡 Tentang apa?</button>
                        <button @click="quickChat('kasih clue dong')" class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded-full hover:bg-purple-100">🔍 Kasih clue</button>
                        <button @click="quickChat('cara mengerjakan?')" class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded-full hover:bg-purple-100">📚 Caranya?</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-2xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-2">Kumpulkan Ujian?</h3>
            <p class="text-gray-600 mb-4">Pastikan semua jawaban sudah benar.</p>
            <div class="bg-gray-50 rounded-xl p-3 mb-4 text-sm">
                <p>✓ Terjawab: <strong x-text="Object.keys(jawaban).filter(k => jawaban[k]).length"></strong></p>
                <p>⚠ Ragu-ragu: <strong x-text="raguRagu.length"></strong></p>
                <p>○ Kosong: <strong x-text="{{ $soalList->count() }} - Object.keys(jawaban).filter(k => jawaban[k]).length"></strong></p>
            </div>
            <div class="flex gap-3">
                <button @click="showModal = false" class="flex-1 px-4 py-2 bg-gray-200 rounded-xl font-bold">Batal</button>
                <button @click="submitExam()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-xl font-bold">Ya, Kumpulkan</button>
            </div>
        </div>
    </div>

    <script>
    function examApp(initialTime, mode) {
        return {
            currentSoal: 0,
            jawaban: {},
            raguRagu: [],
            timeLeft: initialTime,
            showModal: false,
            chatInput: '',
            tracking: { tabSwitch: 0, copy: 0, paste: 0, rightClick: 0 },
            
            init() {
                if (mode === 'ujian' && this.timeLeft > 0) {
                    setInterval(() => {
                        this.timeLeft--;
                        if (this.timeLeft <= 0) this.submitExam();
                    }, 1000);
                }
                
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) this.tracking.tabSwitch++;
                });
            },
            
            toggleRagu(soalId) {
                const idx = this.raguRagu.indexOf(soalId);
                if (idx > -1) {
                    this.raguRagu.splice(idx, 1);
                } else {
                    this.raguRagu.push(soalId);
                }
            },
            
            sendChat() {
                if (!this.chatInput.trim()) return;
                
                const container = document.getElementById('chatMessages');
                const userMsg = document.createElement('div');
                userMsg.className = 'flex gap-2 justify-end';
                userMsg.innerHTML = `
                    <div class="bg-blue-500 text-white rounded-2xl rounded-tr-none p-3 max-w-xs">
                        <p class="text-sm">${this.escapeHtml(this.chatInput)}</p>
                    </div>
                `;
                container.appendChild(userMsg);
                
                const question = this.chatInput.toLowerCase();
                this.chatInput = '';
                
                setTimeout(() => {
                    const botMsg = document.createElement('div');
                    botMsg.className = 'flex gap-2';
                    botMsg.innerHTML = `
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-purple-600 text-sm"></i>
                        </div>
                        <div class="bg-purple-50 rounded-2xl rounded-tl-none p-3 max-w-xs">
                            <p class="text-sm text-gray-800">${this.getAIResponse(question)}</p>
                        </div>
                    `;
                    container.appendChild(botMsg);
                    container.scrollTop = container.scrollHeight;
                }, 500);
                
                container.scrollTop = container.scrollHeight;
            },
            
            quickChat(text) {
                this.chatInput = text;
                this.sendChat();
            },
            
            getAIResponse(question) {
                // AI hanya kasih ARAHAN, bukan jawaban!
                if (question.includes('tentang apa') || question.includes('apa')) {
                    return 'Soal ini sepertinya tentang konsep dasar. Coba baca kata kuncinya dulu ya! 🔍';
                }
                if (question.includes('clue') || question.includes('petunjuk')) {
                    return 'Clue: Coba pikirkan dari sudut pandang yang berbeda. Apa yang sudah kamu pelajari tentang topik ini? 💡';
                }
                if (question.includes('cara') || question.includes('caranya')) {
                    return 'Cara terbaik: 1) Baca soal dengan teliti 2) Identifikasi yang diketahui 3) Pikirkan rumus/konsep yang relevan 📚';
                }
                if (question.includes('bingung')) {
                    return 'Jangan bingung! Coba breakdown soal jadi bagian-bagian kecil. Kamu pasti bisa! 💪';
                }
                return 'Aku tidak bisa kasih jawaban langsung, tapi coba fokus pada kata kunci di soal. Apa yang ditanyakan? 🤔';
            },
            
            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            },
            
            formatTime(seconds) {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            },
            
            confirmSubmit() { this.showModal = true; },
            submitExam() { document.getElementById('examForm').submit(); }
        }
    }
    </script>
</div>
@endsection