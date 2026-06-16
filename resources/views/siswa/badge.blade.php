@extends('layouts.app')

@section('title', 'Badge & Achievement')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Badge & Achievement 🏆</h1>
        <p class="text-gray-600 mt-1">Koleksi badge dan pencapaianmu</p>
    </div>

    <!-- Coming Soon Banner -->
    <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-medal text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Segera Hadir!</h2>
                    <p class="text-purple-100">Fitur Badge sedang dalam pengembangan</p>
                </div>
            </div>
            <p class="text-purple-100 mb-6">
                Kumpulkan badge dengan menyelesaikan ujian, mencapai nilai sempurna, dan aktif belajar setiap hari!
            </p>
            <div class="flex gap-3">
                <a href="{{ route('siswa.dashboard') }}" class="px-6 py-3 bg-white text-purple-600 rounded-2xl hover:bg-purple-50 transition-colors font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white bg-opacity-10 rounded-full translate-y-24 -translate-x-24"></div>
    </div>

    <!-- Preview Badge (Dummy) -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Badge</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @for($i = 1; $i <= 6; $i++)
                <div class="aspect-square bg-gray-100 rounded-2xl flex items-center justify-center relative group">
                    <i class="fas fa-lock text-3xl text-gray-300"></i>
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <p class="text-white text-xs text-center px-2">Terkunci</p>
                    </div>
                </div>
            @endfor
        </div>
        <p class="text-sm text-gray-500 mt-4 text-center">
            <i class="fas fa-info-circle mr-1"></i>
            Badge akan terbuka setelah kamu menyelesaikan tantangan tertentu
        </p>
    </div>
</div>
@endsection