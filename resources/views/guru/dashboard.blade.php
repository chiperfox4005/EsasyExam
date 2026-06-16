@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-blue-100 text-lg mb-4">Pantau perkembangan ujian dan performa siswa Anda di sini.</p>
            
            <div class="flex gap-3">
                <a href="{{ route('ujian.create') }}" class="px-6 py-3 bg-white text-blue-600 rounded-2xl hover:bg-blue-50 transition-colors font-semibold shadow-md">
                    <i class="fas fa-plus mr-2"></i> Buat Ujian Baru
                </a>
                <a href="{{ route('bank-soal.create') }}" class="px-6 py-3 bg-blue-500 bg-opacity-30 text-white border border-white border-opacity-50 rounded-2xl hover:bg-opacity-40 transition-colors font-semibold backdrop-blur-sm">
                    <i class="fas fa-database mr-2"></i> Tambah Soal
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white bg-opacity-10 rounded-full translate-y-24 -translate-x-24"></div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Ujian -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-200 card-hover">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clipboard-list text-2xl text-white"></i>
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $totalUjian }}</h3>
            <p class="text-sm text-gray-600 mt-1">Ujian Dibuat</p>
        </div>

        <!-- Total Peserta -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-200 card-hover">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $totalPeserta }}</h3>
            <p class="text-sm text-gray-600 mt-1">Siswa Mengerjakan</p>
        </div>

        <!-- Rata-rata Nilai -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-200 card-hover">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-star text-2xl text-white"></i>
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Rata-rata</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($rataRataNilai, 1) }}</h3>
            <p class="text-sm text-gray-600 mt-1">Nilai Keseluruhan</p>
        </div>
    </div>

    <!-- Chart & Recent Ujian -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-lg border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Statistik Nilai per Mapel</h3>
                    <p class="text-sm text-gray-600">Rata-rata nilai siswa berdasarkan mata pelajaran</p>
                </div>
            </div>
            
            <div class="relative h-64">
                <canvas id="nilaiChart"></canvas>
            </div>
            
            @if(count($mapelLabels) === 0)
                <div class="text-center py-8">
                    <i class="fas fa-chart-bar text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-600">Belum ada data nilai</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('ujian.index') }}" class="flex items-center gap-4 p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition-colors group">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform shadow-md">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Kelola Ujian</p>
                        <p class="text-xs text-gray-600">Lihat & edit ujian</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                
                <a href="{{ route('bank-soal.index') }}" class="flex items-center gap-4 p-4 bg-purple-50 rounded-2xl hover:bg-purple-100 transition-colors group">
                    <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform shadow-md">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Bank Soal</p>
                        <p class="text-xs text-gray-600">Kelola kumpulan soal</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>

                <a href="{{ route('mapel.index') }}" class="flex items-center gap-4 p-4 bg-green-50 rounded-2xl hover:bg-green-100 transition-colors group">
                    <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform shadow-md">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Mata Pelajaran</p>
                        <p class="text-xs text-gray-600">Atur mapel mengajar</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Ujian Table -->
    <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Ujian Terbaru</h3>
            <a href="{{ route('ujian.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-700 text-sm">
                    <tr>
                        <th class="p-4 rounded-l-2xl font-semibold">Judul Ujian</th>
                        <th class="p-4 font-semibold">Mapel</th>
                        <th class="p-4 text-center font-semibold">Peserta</th>
                        <th class="p-4 text-center font-semibold">Rata-rata</th>
                        <th class="p-4 rounded-r-2xl text-right font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ujianTerbaru as $ujian)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-semibold text-gray-900">
                                {{ $ujian->judul }}
                                <p class="text-xs text-gray-600 mt-1">{{ $ujian->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="p-4 text-gray-700">
                                <span class="flex items-center gap-2">
                                    <i class="fas {{ $ujian->mapel->icon ?? 'fa-book' }} text-blue-600"></i>
                                    {{ $ujian->mapel->nama }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    {{ $ujian->jumlah_peserta }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold text-gray-900 {{ $ujian->rata_rata >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($ujian->rata_rata, 1) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $ujian->status == 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($ujian->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-600">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada ujian yang dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('nilaiChart').getContext('2d');
    const labels = @json($mapelLabels);
    const data = @json($nilaiData);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: data,
                backgroundColor: 'rgba(37, 99, 235, 0.8)',
                borderColor: '#2563EB',
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
</script>
@endpush
@endsection