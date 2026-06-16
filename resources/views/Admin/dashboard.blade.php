@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Welcome Section -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-600 mt-1">Berikut ringkasan aktivitas platform EsasyExam hari ini</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-white border border-gray-200 rounded-2xl text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Export
            </button>
            <button class="px-4 py-2 gradient-primary text-white rounded-2xl hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/30">
                <i class="fas fa-plus mr-2"></i>
                Tambah Data
            </button>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Guru -->
        <div class="bg-white rounded-3xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i>+12%
                </span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_guru'] }}</h3>
            <p class="text-gray-600 text-sm mt-1">Total Guru</p>
            <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 rounded-full" style="width: 75%"></div>
            </div>
        </div>
        
        <!-- Total Siswa -->
        <div class="bg-white rounded-3xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-yellow-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i>+8%
                </span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_siswa'] }}</h3>
            <p class="text-gray-600 text-sm mt-1">Total Siswa</p>
            <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-500 rounded-full" style="width: 65%"></div>
            </div>
        </div>
        
        <!-- Total Kelas -->
        <div class="bg-white rounded-3xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-school text-green-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i>+5%
                </span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_kelas'] ?? 0 }}</h3>
            <p class="text-gray-600 text-sm mt-1">Total Kelas</p>
            <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full" style="width: 50%"></div>
            </div>
        </div>
        
        <!-- Total Mapel -->
        <div class="bg-white rounded-3xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-book text-purple-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i>+3%
                </span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_mapel'] ?? 0 }}</h3>
            <p class="text-gray-600 text-sm mt-1">Total Mapel</p>
            <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-purple-500 rounded-full" style="width: 40%"></div>
            </div>
        </div>
    </div>
    
    <!-- Charts & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Statistik Pengguna</h3>
                    <p class="text-sm text-gray-600">6 bulan terakhir</p>
                </div>
                <select class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Bulan Ini</option>
                    <option>Minggu Ini</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="relative" style="height: 300px; max-height: 300px;">
                <canvas id="userChart"></canvas>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Aktivitas Terbaru</h3>
                <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Guru baru terdaftar</p>
                        <p class="text-xs text-gray-500 mt-1">Budi Santoso - Matematika</p>
                        <p class="text-xs text-gray-400 mt-1">2 menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Ujian selesai</p>
                        <p class="text-xs text-gray-500 mt-1">Kelas XII IPA 1 - Fisika</p>
                        <p class="text-xs text-gray-400 mt-1">15 menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Soal baru ditambahkan</p>
                        <p class="text-xs text-gray-500 mt-1">50 soal PG - Biologi</p>
                        <p class="text-xs text-gray-400 mt-1">1 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection