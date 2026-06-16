<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $role = 'siswa';
    public string $email = '';
    public string $nisn = '';
    public string $password = '';
    public bool $remember = false;

    // Method untuk mengubah role (dipanggil saat klik card)
    public function setRole($newRole): void
    {
        $this->role = $newRole;
        // Reset field yang tidak relevan
        if ($newRole === 'siswa') {
            $this->email = '';
        } else {
            $this->nisn = '';
        }
    }

    public function login(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if ($this->role === 'siswa') {
            $this->validate([
                'nisn' => ['required', 'string'],
            ]);
            
            $user = \App\Models\User::where('nisn', $this->nisn)
                ->where('role', 'siswa')
                ->first();
            
            if (!$user || !\Hash::check($this->password, $user->password)) {
                throw ValidationException::withMessages([
                    'nisn' => __('NISN atau password salah.'),
                ]);
            }
        } else {
            $this->validate([
                'email' => ['required', 'string', 'email'],
            ]);
            
            $user = \App\Models\User::where('email', $this->email)
                ->where('role', $this->role)
                ->first();
            
            if (!$user || !\Hash::check($this->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => __('Email atau password salah.'),
                ]);
            }
        }

        $throttleKey = Str::transliterate($this->role.'|'.request()->ip());
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        Auth::login($user, $this->remember);
        
        $user->update([
            'is_online' => true,
            'last_seen' => now(),
        ]);

        RateLimiter::hit($throttleKey);
        Session::regenerate();

        $redirectPath = match($user->role) {
            'admin' => route('admin.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            default => '/',
        };

        $this->redirect($redirectPath, navigate: true);
    }
} ?>

<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <!-- Logo & Header -->
    <div class="mb-8 text-center">
        <div class="w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900">Selamat Datang 👋</h1>
        <p class="mt-2 text-gray-600">Masuk ke akun EsasyExam Anda</p>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-2xl">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login">
            <!-- Role Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Masuk Sebagai</label>
                <div class="grid grid-cols-3 gap-3">
                    <!-- Siswa -->
                    <button 
                        type="button"
                        wire:click="setRole('siswa')"
                        class="p-4 border-2 rounded-2xl transition-all text-center cursor-pointer {{ $role === 'siswa' ? 'border-blue-500 bg-blue-50 shadow-lg scale-105' : 'border-gray-200 hover:border-blue-300' }}"
                    >
                        <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        </svg>
                        <p class="font-semibold text-gray-900 text-sm">Siswa</p>
                        <p class="text-xs text-gray-500">NISN</p>
                    </button>
                    
                    <!-- Guru -->
                    <button 
                        type="button"
                        wire:click="setRole('guru')"
                        class="p-4 border-2 rounded-2xl transition-all text-center cursor-pointer {{ $role === 'guru' ? 'border-green-500 bg-green-50 shadow-lg scale-105' : 'border-gray-200 hover:border-green-300' }}"
                    >
                        <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="font-semibold text-gray-900 text-sm">Guru</p>
                        <p class="text-xs text-gray-500">Email</p>
                    </button>
                    
                    <!-- Admin -->
                    <button 
                        type="button"
                        wire:click="setRole('admin')"
                        class="p-4 border-2 rounded-2xl transition-all text-center cursor-pointer {{ $role === 'admin' ? 'border-red-500 bg-red-50 shadow-lg scale-105' : 'border-gray-200 hover:border-red-300' }}"
                    >
                        <svg class="w-8 h-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <p class="font-semibold text-gray-900 text-sm">Admin</p>
                        <p class="text-xs text-gray-500">Email</p>
                    </button>
                </div>
            </div>

            <!-- Dynamic Field: Email (for Guru & Admin) -->
            @if ($role === 'guru' || $role === 'admin')
                <div class="mb-4" wire:key="email-field">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <input 
                            id="email" 
                            type="email" 
                            wire:model="email" 
                            required 
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Dynamic Field: NISN (for Siswa) -->
            @if ($role === 'siswa')
                <div class="mb-4" wire:key="nisn-field">
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        <input 
                            id="nisn" 
                            type="text" 
                            wire:model="nisn" 
                            required 
                            autocomplete="nisn"
                            placeholder="Contoh: 0012345678"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan NISN Anda</p>
                    @error('nisn')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input 
                        id="password" 
                        type="password" 
                        wire:model="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input 
                        wire:model="remember" 
                        id="remember" 
                        type="checkbox" 
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    >
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700" wire:navigate>
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-2xl font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30 disabled:opacity-50"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk sebagai {{ ucfirst($role) }}
                </span>
                <span wire:loading>
                    <svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>

            <!-- Register Link -->
            <div class="mt-6 text-center text-sm">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold" wire:navigate>
                    Daftar sekarang
                </a>
            </div>
        </form>
    </div>

    <!-- Demo Credentials -->
    <div class="mt-6 p-4 bg-white rounded-2xl shadow-md border border-blue-100 max-w-md w-full">
        <p class="text-sm font-semibold text-blue-900 mb-3">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Demo Credentials:
        </p>
        <div class="space-y-2 text-xs text-blue-800">
            <div class="flex justify-between items-center">
                <span><strong>Admin:</strong> admin@esasyexam.com</span>
                <span class="font-mono bg-blue-50 px-2 py-1 rounded">password</span>
            </div>
            <div class="flex justify-between items-center">
                <span><strong>Guru:</strong> guru@esasyexam.com</span>
                <span class="font-mono bg-blue-50 px-2 py-1 rounded">password</span>
            </div>
            <div class="flex justify-between items-center">
                <span><strong>Siswa:</strong> 0012345678</span>
                <span class="font-mono bg-blue-50 px-2 py-1 rounded">password</span>
            </div>
        </div>
    </div>
</div>