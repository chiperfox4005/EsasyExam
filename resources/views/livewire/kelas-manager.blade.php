<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Kelas </h1>
            <p class="text-gray-600 mt-1">Kelola kelas formal dan virtual</p>
        </div>
        <button wire:click="$set('editingId', null)" class="px-4 py-2 bg-blue-600 text-white rounded-3xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus mr-2"></i> Tambah Kelas
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-3xl flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Form Input -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingId ? 'Edit Kelas' : 'Tambah Kelas Baru' }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                <input wire:model="nama" type="text" placeholder="Contoh: XII IPA 1" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                <input wire:model="tingkat" type="number" placeholder="1-12" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('tingkat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select wire:model="tipe" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="formal">Formal (Sekolah)</option>
                    <option value="virtual">Virtual (Les Online)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas</label>
                <select wire:model="wali_kelas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button wire:click="{{ $editingId ? 'update' : 'store' }}" class="px-6 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i> {{ $editingId ? 'Update' : 'Simpan' }}
            </button>
            @if($editingId)
                <button wire:click="resetForm" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-colors">Batal</button>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Daftar Kelas</h3>
            <input wire:model.live="search" type="text" placeholder="Cari kelas..." class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="p-4 rounded-l-2xl">Nama</th>
                        <th class="p-4">Tingkat</th>
                        <th class="p-4">Tipe</th>
                        <th class="p-4">Wali Kelas</th>
                        <th class="p-4 rounded-r-2xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kelas as $k)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-900">{{ $k->nama }}</td>
                            <td class="p-4">Tingkat {{ $k->tingkat }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 text-xs rounded-full {{ $k->tipe == 'formal' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ ucfirst($k->tipe) }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-600">{{ $k->waliKelas->name ?? '-' }}</td>
                            <td class="p-4 text-right space-x-2">
                                <button wire:click="edit({{ $k->id }})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $k->id }})" wire:confirm="Yakin hapus kelas ini?" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada data kelas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $kelas->links() }}
        </div>
    </div>
</div>