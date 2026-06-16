<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Ujian 📝</h1>
            <p class="text-gray-600 mt-1">Buat jadwal ujian dan pilih soal dari bank soal</p>
        </div>
        @if(!$showForm)
            <button wire:click="openCreateForm" class="px-4 py-2 bg-blue-600 text-white rounded-3xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                <i class="fas fa-plus mr-2"></i> Buat Ujian Baru
            </button>
        @endif
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-3xl flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- FORM UJIAN -->
    @if($showForm)
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 animate-fadeIn">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Formulir Ujian Baru</h3>
                <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Mode Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Mode</label>
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" wire:click="$set('mode', 'latihan')" class="p-4 border-2 rounded-2xl transition-all text-left {{ $mode === 'latihan' ? 'border-green-500 bg-green-50 shadow-lg' : 'border-gray-200 hover:border-green-300' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-book-reader text-2xl text-green-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Latihan</p>
                                <p class="text-xs text-gray-500">Tanpa waktu, ada feedback jawaban</p>
                            </div>
                        </div>
                    </button>
                    <button type="button" wire:click="$set('mode', 'ujian')" class="p-4 border-2 rounded-2xl transition-all text-left {{ $mode === 'ujian' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200 hover:border-blue-300' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clipboard-check text-2xl text-blue-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Ujian</p>
                                <p class="text-xs text-gray-500">Dengan waktu & anti-cheat</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input wire:model="judul" id="judul" name="judul" type="text" placeholder="Contoh: UTS Matematika" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select wire:model="tipe" id="tipe" name="tipe" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="quiz">Quiz</option>
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                        <option value="latihan">Latihan</option>
                    </select>
                </div>
                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select wire:model.live="mapel_id" id="mapel_id" name="mapel_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                    @error('mapel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas (Opsional)</label>
                    <select wire:model="kelas_id" id="kelas_id" name="kelas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if($mode === 'ujian')
                    <div>
                        <label for="durasi_menit" class="block text-sm font-medium text-gray-700 mb-1">Durasi (Menit)</label>
                        <input wire:model="durasi_menit" id="durasi_menit" name="durasi_menit" type="number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('durasi_menit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="mulai_at" class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                        <input wire:model="mulai_at" id="mulai_at" name="mulai_at" type="datetime-local" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('mulai_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="selesai_at" class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                        <input wire:model="selesai_at" id="selesai_at" name="selesai_at" type="datetime-local" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('selesai_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>

            <!-- Anti-Cheat Settings -->
            @if($mode === 'ujian')
                <div class="mt-6 p-4 bg-red-50 rounded-2xl border border-red-100">
                    <h4 class="font-bold text-red-900 mb-3"><i class="fas fa-shield-alt mr-2"></i>Pengaturan Anti-Cheat</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="deteksi_tab_switch" class="rounded text-red-600">
                            <span class="text-sm text-gray-700">Deteksi Pindah Tab</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="boleh_copy_paste" class="rounded text-red-600">
                            <span class="text-sm text-gray-700">Izinkan Copy-Paste</span>
                        </label>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Maks. Pindah Tab</label>
                            <input wire:model="max_tab_switch" type="number" min="1" max="10" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pilih Soal -->
            @if($mapel_id)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Soal ({{ count($selectedSoal) }} dipilih)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 bg-gray-50 rounded-2xl border border-gray-200">
                        @forelse($availableSoal as $soal)
                            <label class="flex items-start gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:bg-blue-50 transition-colors">
                                <input type="checkbox" wire:model="selectedSoal" value="{{ $soal->id }}" class="mt-1 text-blue-600 rounded">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit($soal->pertanyaan, 60) }}</p>
                                    <span class="text-xs text-gray-500">{{ strtoupper($soal->tipe) }} • {{ ucfirst($soal->level) }}</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-gray-500 text-sm col-span-2">Tidak ada soal yang dipublish untuk mapel ini.</p>
                        @endforelse
                    </div>
                    @error('selectedSoal') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            @else
                <div class="mt-6 p-4 bg-yellow-50 rounded-2xl border border-yellow-200">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pilih mata pelajaran terlebih dahulu untuk melihat daftar soal yang tersedia.
                    </p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-2">
                <button wire:click="store" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> Publish
                </button>
                <button wire:click="closeForm" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">
                    Batal
                </button>
            </div>
        </div>
    @endif

    <!-- DAFTAR UJIAN -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Ujian Saya</h3>
        <div class="space-y-4">
            @forelse($ujian as $u)
                <div class="border border-gray-100 rounded-3xl p-5 hover:shadow-md transition-shadow bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ $u->judul }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $u->mapel->nama }} • {{ $u->durasi_menit }} Menit • {{ $u->soalUjian->count() }} Soal
                                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $u->mode === 'latihan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($u->mode) }}
                                </span>
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full {{ $u->status == 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ ucfirst($u->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-xs text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i> 
                            {{ \Carbon\Carbon::parse($u->mulai_at)->format('d M Y, H:i') }} - 
                            {{ \Carbon\Carbon::parse($u->selesai_at)->format('d M Y, H:i') }}
                        </div>
                        <button wire:click="delete({{ $u->id }})" wire:confirm="Yakin hapus ujian ini?" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p>Belum ada ujian yang dibuat.</p>
                    <p class="text-sm mt-2">Klik tombol "Buat Ujian Baru" untuk memulai.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $ujian->links() }}
        </div>
    </div>
</div>