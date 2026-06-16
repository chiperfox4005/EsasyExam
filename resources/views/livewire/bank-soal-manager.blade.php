<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Bank Soal </h1>
            <p class="text-gray-600 mt-1">Kelola soal PG, Essay, dan Gambar</p>
        </div>
        <button wire:click="$set('editingId', null)" class="px-4 py-2 bg-blue-600 text-white rounded-3xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus mr-2"></i> Tambah Soal
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-3xl flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Form Input Soal -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingId ? 'Edit Soal' : 'Buat Soal Baru' }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select wire:model="mapel_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapelList as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                    @endforeach
                </select>
                @error('mapel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Soal</label>
                <select wire:model="tipe" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="pg">Pilihan Ganda</option>
                    <option value="essay">Essay</option>
                    <option value="gambar">Soal Gambar</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Level Kesulitan</label>
                <select wire:model="level" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="mudah">Mudah</option>
                    <option value="sedang">Sedang</option>
                    <option value="sulit">Sulit</option>
                </select>
            </div>
        </div>

        <!-- Pertanyaan -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
            <textarea wire:model="pertanyaan" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Tulis pertanyaan di sini..."></textarea>
            @error('pertanyaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Form Dinamis Berdasarkan Tipe -->
        @if($tipe === 'pg')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <input type="radio" wire:model="jawaban" value="A" class="text-blue-600">
                    <span class="font-bold text-gray-500">A.</span>
                    <input wire:model="opsi_a" type="text" placeholder="Opsi A" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <input type="radio" wire:model="jawaban" value="B" class="text-blue-600">
                    <span class="font-bold text-gray-500">B.</span>
                    <input wire:model="opsi_b" type="text" placeholder="Opsi B" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <input type="radio" wire:model="jawaban" value="C" class="text-blue-600">
                    <span class="font-bold text-gray-500">C.</span>
                    <input wire:model="opsi_c" type="text" placeholder="Opsi C" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <input type="radio" wire:model="jawaban" value="D" class="text-blue-600">
                    <span class="font-bold text-gray-500">D.</span>
                    <input wire:model="opsi_d" type="text" placeholder="Opsi D" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="flex items-center gap-2 md:col-span-2">
                    <input type="radio" wire:model="jawaban" value="E" class="text-blue-600">
                    <span class="font-bold text-gray-500">E.</span>
                    <input wire:model="opsi_e" type="text" placeholder="Opsi E" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
        @elseif($tipe === 'essay')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kunci Jawaban / Panduan</label>
                <textarea wire:model="jawaban" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Tulis kunci jawaban..."></textarea>
            </div>
        @elseif($tipe === 'gambar')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Soal</label>
                <input wire:model="gambar_soal" type="file" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('gambar_soal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @if ($gambar_soal)
                    <img src="{{ $gambar_soal->temporaryUrl() }}" class="mt-2 h-32 rounded-2xl object-cover">
                @endif
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kunci Jawaban (Opsional)</label>
                <input wire:model="jawaban" type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
        @endif

        <!-- Status & Submit -->
        <div class="flex items-center justify-between mt-6">
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" wire:model="status" value="draft" class="text-blue-600">
                    <span class="text-sm text-gray-700">Simpan sebagai Draft</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" wire:model="status" value="published" class="text-green-600">
                    <span class="text-sm text-gray-700">Publish Sekarang</span>
                </label>
            </div>
            <div class="flex gap-2">
                @if($editingId)
                    <button wire:click="resetForm" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">Batal</button>
                @endif
                <button wire:click="{{ $editingId ? 'update' : 'store' }}" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> {{ $editingId ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Soal -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h3 class="text-lg font-bold text-gray-900">Daftar Soal Saya</h3>
            <div class="flex gap-2 w-full md:w-auto">
                <input wire:model.live="search" type="text" placeholder="Cari soal..." class="flex-1 md:w-64 px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <select wire:model.live="filterLevel" class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Semua Level</option>
                    <option value="mudah">Mudah</option>
                    <option value="sedang">Sedang</option>
                    <option value="sulit">Sulit</option>
                </select>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($soal as $s)
                <div class="border border-gray-100 rounded-3xl p-5 hover:shadow-md transition-shadow bg-gray-50">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $s->tipe == 'pg' ? 'bg-blue-100 text-blue-700' : ($s->tipe == 'essay' ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ strtoupper($s->tipe) }}
                            </span>
                            <span class="px-3 py-1 text-xs rounded-full {{ $s->level == 'mudah' ? 'bg-green-100 text-green-700' : ($s->level == 'sedang' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($s->level) }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $s->mapel->nama }}</span>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full {{ $s->status == 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </div>
                    
                    <p class="text-gray-900 font-medium mb-3">{{ Str::limit($s->pertanyaan, 150) }}</p>
                    
                    @if($s->gambar_soal)
                        <img src="{{ asset('storage/' . $s->gambar_soal) }}" class="h-24 rounded-2xl object-cover mb-3">
                    @endif

                    <div class="flex justify-end gap-2 mt-4">
                        <button wire:click="edit({{ $s->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Yakin hapus soal ini?" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p>Belum ada soal. Yuk buat soal pertama!</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $soal->links() }}
        </div>
    </div>
</div>