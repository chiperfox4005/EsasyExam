<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mata Pelajaran 📚</h1>
            <p class="text-gray-600 mt-1">Kelola mata pelajaran dan penugasan guru</p>
        </div>
        <button wire:click="$set('editingId', 'create')" class="px-4 py-2 bg-blue-600 text-white rounded-3xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus mr-2"></i> Tambah Mapel
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-3xl flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Form Input -->
    @if($editingId)
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                {{ $editingId === 'create' ? 'Tambah Mata Pelajaran Baru' : 'Edit Mata Pelajaran' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mapel</label>
                    <input wire:model="nama" id="nama" name="nama" type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                    <input wire:model="kode" id="kode" name="kode" type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('kode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome)</label>
                    <input wire:model="icon" id="icon" name="icon" type="text" placeholder="fa-book" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select wire:model="kelas_id" id="kelas_id" name="kelas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Umum / Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
                    <select wire:model="guru_id" id="guru_id" name="guru_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi" id="deskripsi" name="deskripsi" rows="1" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button wire:click="{{ $editingId === 'create' ? 'store' : 'update' }}" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> {{ $editingId === 'create' ? 'Simpan' : 'Update' }}
                </button>
                <button wire:click="$set('editingId', null)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">Batal</button>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Daftar Mata Pelajaran</h3>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari mapel..." class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="p-4 rounded-l-2xl">Mapel</th>
                        <th class="p-4">Kode</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Guru</th>
                        <th class="p-4 rounded-r-2xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mapel as $m)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-900 flex items-center gap-2">
                                <i class="fas {{ $m->icon }} text-blue-600"></i> {{ $m->nama }}
                            </td>
                            <td class="p-4 text-gray-600">{{ $m->kode }}</td>
                            <td class="p-4 text-gray-600">{{ $m->kelas->nama ?? 'Umum' }}</td>
                            <td class="p-4 text-gray-600">{{ $m->guru->name ?? '-' }}</td>
                            <td class="p-4 text-right space-x-2">
                                <button wire:click="edit({{ $m->id }})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $m->id }})" wire:confirm="Yakin hapus mapel ini?" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada data mata pelajaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $mapel->links() }}
        </div>
    </div>
</div>