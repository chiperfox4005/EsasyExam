<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
>
    <div class="flex flex-col h-full">

        {{-- HEADER --}}
        <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-100">

            <div class="w-10 h-10 gradient-primary rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fas fa-graduation-cap text-white text-lg"></i>
            </div>

            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    EsasyExam
                </h1>

                <p class="text-xs text-gray-500">
                    Smart Learning
                </p>
            </div>

        </div>


        {{-- MENU --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

            @php
                $role = auth()->user()->role;
            @endphp


            {{-- ================= ADMIN ================= --}}
            @if($role === 'admin')

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }} transition-all duration-200">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('kelas.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('kelas.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }} transition-all duration-200">
                    <i class="fas fa-school w-5"></i>
                    <span class="font-medium">Kelola Kelas</span>
                </a>

                <a href="{{ route('mapel.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('mapel.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-book w-5"></i>
                    <span class="font-medium">Kelola Mapel</span>
                </a>

                <a href="{{ route('guru.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('guru.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-chalkboard-teacher w-5"></i>
                    <span class="font-medium">Kelola Guru</span>
                </a>

                <a href="{{ route('siswa.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('siswa.index') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-user-graduate w-5"></i>
                    <span class="font-medium">Kelola Siswa</span>
                </a>

                <a href="{{ route('ujian.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('ujian.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span class="font-medium">Kelola Ujian</span>
                </a>


            {{-- ================= GURU ================= --}}
            @elseif($role === 'guru')

                <a href="{{ route('guru.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('guru.dashboard') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('mapel.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('mapel.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-book w-5"></i>
                    <span class="font-medium">Mata Pelajaran</span>
                </a>

                {{-- BANK SOAL --}}
                <a href="{{ route('bank-soal.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('bank-soal.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }} transition-all duration-200">
                    <i class="fas fa-database w-5"></i>
                    <span class="font-medium">Bank Soal</span>
                </a>

                {{-- AI GENERATE (MENU BARU) --}}
                <a href="{{ route('ai-generate.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('ai-generate.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }} transition-all duration-200">
                    <i class="fas fa-robot w-5"></i>
                    <span class="font-medium">AI Generate</span>
                    <span class="ml-auto px-2 py-0.5 text-xs bg-purple-500 text-white rounded-full font-semibold">NEW</span>
                </a>

                <a href="{{ route('ujian.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('ujian.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span class="font-medium">Ujian</span>
                </a>


            {{-- ================= SISWA ================= --}}
            @elseif($role === 'siswa')

                <a href="{{ route('siswa.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('siswa.dashboard') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('siswa.belajar') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('siswa.belajar') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-book-reader w-5"></i>
                    <span class="font-medium">Belajar</span>
                </a>

                <a href="{{ route('siswa.ujian.daftar') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('siswa.ujian.*') ? 'gradient-primary text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-tasks w-5"></i>
                    <span class="font-medium">Ujian</span>
                </a>

                <a href="{{ route('siswa.ai-mentor') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl">
                    <i class="fas fa-robot w-5"></i>
                    <span class="font-medium">AI Mentor</span>
                </a>

                <a href="{{ route('siswa.badge') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl">
                    <i class="fas fa-medal w-5"></i>
                    <span class="font-medium">Badge</span>
                </a>

                <a href="{{ route('siswa.leaderboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl">
                    <i class="fas fa-trophy w-5"></i>
                    <span class="font-medium">Leaderboard</span>
                </a>

            @endif

        </nav>


        {{-- PROFILE --}}
        <div class="p-4 border-t border-gray-100">

            <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50">

                <img
                    src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                    class="w-10 h-10 rounded-2xl"
                >

                <div class="flex-1 min-w-0">

                    <p class="text-sm font-semibold text-gray-900 truncate">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500 capitalize">
                        {{ auth()->user()->role }}
                    </p>

                </div>

            </div>

        </div>

    </div>
</aside>

<div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
></div>