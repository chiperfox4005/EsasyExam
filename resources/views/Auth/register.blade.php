<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Akun</h1>
        <p class="mt-2 text-gray-600">Bergabung dengan EsasyExam</p>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="{ role: 'siswa' }">
        @csrf

        <!-- Role Selection -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <label class="cursor-pointer">
                <input type="radio" name="role" value="siswa" x-model="role" class="peer sr-only" checked>
                <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                    <i class="fas fa-user-graduate text-2xl text-blue-600 mb-2"></i>
                    <p class="font-semibold text-gray-900">Siswa</p>
                    <p class="text-xs text-gray-500">Login dengan NISN</p>
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="role" value="guru" x-model="role" class="peer sr-only">
                <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                    <i class="fas fa-chalkboard-teacher text-2xl text-green-600 mb-2"></i>
                    <p class="font-semibold text-gray-900">Guru</p>
                    <p class="text-xs text-gray-500">Login dengan Email</p>
                </div>
            </label>
        </div>

        <!-- Name -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NISN (Siswa Only) -->
        <div class="mt-4" x-show="role === 'siswa'">
            <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
            <input id="nisn" type="text" name="nisn" value="{{ old('nisn') }}" 
                placeholder="Contoh: 0012345678"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">NISN akan digunakan sebagai username login</p>
            @error('nisn')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email (Guru Only) -->
        <div class="mt-4" x-show="role === 'guru'">
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NIP (Guru Only) -->
        <div class="mt-4" x-show="role === 'guru'">
            <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
            <input id="nip" type="text" name="nip" value="{{ old('nip') }}" 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('nip')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input id="password" type="password" name="password" required 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full mt-6 px-6 py-3 gradient-primary text-white rounded-2xl font-semibold hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/30">
            <i class="fas fa-user-plus mr-2"></i>
            Daftar
        </button>

        <div class="mt-6 text-center text-sm">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>