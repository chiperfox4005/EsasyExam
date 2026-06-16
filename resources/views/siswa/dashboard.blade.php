@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn" x-data="dashboardApp()">
    
    <div class="relative overflow-hidden rounded-3xl shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600"></div>
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-yellow-300 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        </div>
        
        <div class="relative p-8 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm border-2 border-white border-opacity-30 animate-bounce">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm font-medium" x-text="greeting"></p>
                        <h1 class="text-3xl font-bold">{{ auth()->user()->name }}</h1>
                        <p class="text-blue-100 text-sm mt-1">
                            <i class="fas fa-calendar-day mr-1"></i>
                            <span x-text="currentDate"></span>
                        </p>
                    </div>
                </div>
                
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl px-6 py-4 border-2 border-white border-opacity-30">
                    <div class="text-center">
                        <p class="text-4xl font-bold font-mono" x-text="currentTime"></p>
                        <p class="text-sm text-blue-100 mt-1">Waktu Sekarang</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl border-2 border-white border-opacity-20">
                <p class="text-lg" x-text="motivationalMessage"></p>
            </div>
            
            <div class="flex gap-3 mt-6">
                <a href="{{ route('siswa.ujian.daftar') }}" class="px-5 py-3 bg-white bg-opacity-20 backdrop-blur-sm border-2 border-white border-opacity-30 rounded-2xl font-semibold hover:bg-opacity-30 transition-all">
                    <i class="fas fa-list mr-2"></i> Daftar Ujian
                </a>
                <a href="{{ route('siswa.riwayat') }}" class="px-5 py-3 bg-white text-blue-600 rounded-2xl font-semibold hover:shadow-xl transition-all">
                    <i class="fas fa-history mr-2"></i> Riwayat
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
        <div class="relative">
            <input type="text" 
                   id="dashboardSearch"
                   placeholder="Cari fitur, menu, mata pelajaran, atau ujian..." 
                   class="w-full px-5 py-3 pl-12 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-700 font-medium placeholder-gray-400">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
        </div>
        
        <div id="searchResults" class="mt-3 space-y-2 hidden"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="group bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-100 hover:border-blue-300 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-clipboard-list text-xl text-white"></i>
                </div>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Aktif</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUjianAktif }}</p>
            <p class="text-sm text-gray-600 font-medium mt-1">Ujian Tersedia</p>
        </div>

        <div class="group bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-100 hover:border-green-300 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $ujianDikerjakan }}</p>
            <p class="text-sm text-gray-600 font-medium mt-1">Ujian Selesai</p>
        </div>

        <div class="group bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-100 hover:border-yellow-300 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-star text-xl text-white"></i>
                </div>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Nilai</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($rataRataNilai ?? 0, 1) }}</p>
            <p class="text-sm text-gray-600 font-medium mt-1">Rata-rata Nilai</p>
        </div>

        <div class="group bg-white rounded-3xl p-5 shadow-lg border-2 border-gray-100 hover:border-purple-300 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-line text-xl text-white"></i>
                </div>
                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">Progress</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">
                @php
                    $progress = $ujianDikerjakan > 0 ? min(100, ($ujianDikerjakan / max(1, $totalUjianAktif + $ujianDikerjakan)) * 100) : 0;
                @endphp
                {{ number_format($progress, 0) }}%
            </p>
            <p class="text-sm text-gray-600 font-medium mt-1">Progress Belajar</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-100">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-500"></i>
                    Ujian Aktif
                </h3>
                <a href="{{ route('siswa.ujian.daftar') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                    Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            
            @if($ujianAktif->count() > 0)
                <div class="space-y-3" id="dashboardActiveExamsList">
                    @foreach($ujianAktif as $ujian)
                        <div class="exam-card group border-2 border-gray-200 rounded-2xl p-4 hover:border-blue-400 hover:shadow-lg transition-all bg-gradient-to-r from-white to-blue-50" data-title="{{ strtolower($ujian->judul) }}" data-mapel="{{ strtolower($ujian->mapel->nama ?? '') }}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $ujian->mode === 'ujian' ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-green-100 text-green-700 border border-green-300' }}">
                                            {{ $ujian->mode === 'ujian' ? '📝 UJIAN' : '📖 LATIHAN' }}
                                        </span>
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fas {{ $ujian->mapel->icon ?? 'fa-book' }} text-blue-500"></i>
                                            {{ $ujian->mapel->nama ?? '-' }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-base mb-2 group-hover:text-blue-600 transition-colors search-target-title">{{ $ujian->judul }}</h4>
                                    <div class="flex items-center gap-4 text-xs text-gray-600 flex-wrap">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-list-ol text-purple-500"></i>
                                            <strong>{{ $ujian->soalUjian->count() }}</strong> Soal
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-clock text-orange-500"></i>
                                            <strong>{{ $ujian->durasi_menit }}</strong> menit
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-calendar text-red-500"></i>
                                            {{ \Carbon\Carbon::parse($ujian->selesai_at)->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('siswa.ujian.kerjakan', $ujian->id) }}" 
                                   class="flex-shrink-0 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-bold text-sm flex items-center gap-2">
                                    <i class="fas fa-play"></i>
                                    <span>Kerjakan</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-semibold mb-1">Belum ada ujian aktif</p>
                    <p class="text-sm text-gray-500">Tunggu pengumuman dari guru ya!</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-purple-500"></i>
                    Nilai per Mapel
                </h3>
                
                @if(count($mapelLabels) > 0)
                    <div class="relative h-56">
                        <canvas id="nilaiChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Belum ada data nilai</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-history text-blue-500"></i>
                        Terbaru
                    </h3>
                    <a href="{{ route('siswa.riwayat') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">
                        Semua
                    </a>
                </div>
                
                @if($riwayatUjian->count() > 0)
                    <div class="space-y-3">
                        @foreach($riwayatUjian->take(3) as $riwayat)
                            @if($riwayat->submitted_at)
                                <a href="{{ route('siswa.ujian.hasil', $riwayat->id) }}" 
                                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ ($riwayat->nilai ?? 0) >= 75 ? 'bg-green-100' : 'bg-red-100' }}">
                                        <i class="fas {{ ($riwayat->nilai ?? 0) >= 75 ? 'fa-check text-green-600' : 'fa-times text-red-600' }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate group-hover:text-blue-600 transition-colors">
                                            {{ $riwayat->ujian->judul ?? 'Ujian' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $riwayat->ujian->mapel->nama ?? '-' }} • {{ $riwayat->submitted_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="font-bold text-sm {{ ($riwayat->nilai ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($riwayat->nilai ?? 0, 0) }}
                                    </span>
                                </a>
                            @else
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-yellow-50 border border-yellow-200">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-yellow-100">
                                        <i class="fas fa-spinner fa-spin text-yellow-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">
                                            {{ $riwayat->ujian->judul ?? 'Ujian' }}
                                        </p>
                                        <p class="text-xs text-yellow-700">
                                            <i class="fas fa-clock mr-1"></i> Sedang dikerjakan
                                        </p>
                                    </div>
                                    <a href="{{ route('siswa.ujian.kerjakan', $riwayat->id) }}" 
                                       class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg text-xs font-bold hover:bg-yellow-600">
                                        Lanjutkan
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Belum ada riwayat</p>
                    </div>
                @endif
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-3xl p-6 border-2 border-yellow-200">
                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-yellow-500"></i>
                    Tips Belajar
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-yellow-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <p class="text-sm text-gray-700">Kerjakan latihan dulu sebelum ujian</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <p class="text-sm text-gray-700">Baca soal dengan teliti sebelum menjawab</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-pink-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <p class="text-sm text-gray-700">Manajemen waktu dengan baik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- ALPINE.JS APP INITIALIZER ---
    function dashboardApp() {
        return {
            currentTime: '',
            currentDate: '',
            greeting: '',
            motivationalMessage: '',
            
            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },
            
            updateTime() {
                const now = new Date();
                
                // Format time
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                this.currentTime = `${hours}:${minutes}:${seconds}`;
                
                // Format date
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                this.currentDate = now.toLocaleDateString('id-ID', options);
                
                // Greeting based on time
                const hour = now.getHours();
                if (hour >= 4 && hour < 11) {
                    this.greeting = 'Selamat Pagi! 🌅';
                    this.motivationalMessage = 'Pagikan harimu dengan mengerjakan soal atau melihat materi pembelajaran. Semangat! 💪';
                } else if (hour >= 11 && hour < 15) {
                    this.greeting = 'Selamat Siang! ☀️';
                    this.motivationalMessage = 'Jangan lupa istirahat dan makan siang. Lanjutkan belajarmu dengan semangat! 🍽️';
                } else if (hour >= 15 && hour < 18) {
                    this.greeting = 'Selamat Sore! 🌤️';
                    this.motivationalMessage = 'Waktunya menyelesaikan tugas dan ujian. Kamu pasti bisa! 📚';
                } else {
                    this.greeting = 'Selamat Malam! 🌙';
                    this.motivationalMessage = 'Istirahat yang cukup untuk besok kembali belajar dengan segar. 😴';
                }
            }
        };
    }
    
    // --- VANILLA JS DOM CONTROLLER ---
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('dashboardSearch');
        const searchResults = document.getElementById('searchResults');
        
        const menuItems = [
            { name: 'Dashboard', url: '{{ route("siswa.dashboard") }}', icon: 'home' },
            { name: 'Belajar', url: '{{ route("siswa.belajar") }}', icon: 'book-reader' },
            { name: 'Ujian', url: '{{ route("siswa.ujian.daftar") }}', icon: 'tasks' },
            { name: 'AI Mentor', url: '{{ route("siswa.ai-mentor") }}', icon: 'robot' },
            { name: 'Badge', url: '{{ route("siswa.badge") }}', icon: 'medal' },
            { name: 'Leaderboard', url: '{{ route("siswa.leaderboard") }}', icon: 'trophy' },
            { name: 'Riwayat', url: '{{ route("siswa.riwayat") }}', icon: 'history' },
        ];
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                
                if (query.length === 0) {
                    searchResults.classList.add('hidden');
                    // Tampilkan kembali semua card ujian aktif jika pencarian kosong
                    document.querySelectorAll('.exam-card').forEach(el => el.classList.remove('hidden'));
                    return;
                }
                
                const results = menuItems.filter(item => 
                    item.name.toLowerCase().includes(query)
                );
                
                if (results.length > 0) {
                    searchResults.innerHTML = results.map(item => `
                        <a href="${item.url}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fas fa-${item.icon} text-blue-600"></i>
                            <span class="font-medium text-gray-700">${item.name}</span>
                        </a>
                    `).join('');
                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.innerHTML = '<p class="text-gray-500 text-sm p-3">Tidak ditemukan</p>';
                    searchResults.classList.remove('hidden');
                }
            });

            // Opsional: Sembunyikan dropdown hasil jika klik di luar search bar
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        }
        
        // Chart JS Logic
        const ctx = document.getElementById('nilaiChart');
        if (ctx) {
            const labels = @json($mapelLabels);
            const data = @json($nilaiData);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nilai',
                        data: data,
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush