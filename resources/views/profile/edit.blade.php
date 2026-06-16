@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-600 mt-1">Kelola informasi akun Anda</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl">
            <i class="fas fa-check-circle mr-2"></i>
            Profil berhasil diperbarui.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl">
            <i class="fas fa-check-circle mr-2"></i>
            Password berhasil diperbarui.
        </div>
    @endif

    <!-- Profile Info -->
    <div class="bg-white rounded-3xl p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Informasi Profil</h3>
        
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-3xl">
                <div>
                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 capitalize">{{ $user->role }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $user->role === 'siswa' ? 'NISN' : ($user->role === 'guru' ? 'NIP' : 'Email') }}
                </label>
                <input type="text" value="{{ $user->role === 'siswa' ? $user->nisn : ($user->role === 'guru' ? $user->nip : $user->email) }}" 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-gray-500" disabled>
                <p class="text-xs text-gray-500 mt-1">Tidak dapat diubah</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="address" rows="3" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 gradient-primary text-white rounded-2xl font-semibold hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/30">
                <i class="fas fa-save mr-2"></i>
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white rounded-3xl p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Ubah Password</h3>
        
        <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                <input type="password" name="current_password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input type="password" name="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="px-6 py-3 gradient-primary text-white rounded-2xl font-semibold hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/30">
                <i class="fas fa-key mr-2"></i>
                Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection