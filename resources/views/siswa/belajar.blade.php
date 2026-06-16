@extends('layouts.app')

@section('title', 'Belajar')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Belajar 📚</h1>
        <p class="text-gray-600 mt-1">Materi pembelajaran interaktif</p>
    </div>

    <!-- Coming Soon Banner -->
    <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-book-reader text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Segera Hadir!</h2>
                    <p class="text-blue-100">Fitur Belajar sedang dalam pengembangan</p>
                </div>
            </div>
            <p class="text-blue-100 mb-6">
                Akses materi pembelajaran interaktif, video tutorial, dan latihan soal kapan saja!
            </p>
            <a href="{{ route('siswa.dashboard') }}" class="px-6 py-3 bg-white text-blue-600 rounded-2xl hover:bg-blue-50 transition-colors font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
    </div>

    <!-- Preview Materi (Dummy) -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Materi Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @for($i = 1; $i <= 3; $i++)
                <div class="border border-gray-200 rounded-2xl p-4 opacity-50">
                    <div class="w-full h-32 bg-gray-200 rounded-xl mb-3 flex items-center justify-center">
                        <i class="fas fa-lock text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="font-semibold text-gray-700 mb-1">Materi {{ $i }}</h4>
                    <p class="text-sm text-gray-500">Segera tersedia</p>
                </div>
            @endfor
        </div>
    </div>
</div>
@endsection