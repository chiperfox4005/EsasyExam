@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Leaderboard 🏆</h1>
        <p class="text-gray-600 mt-1">Peringkat siswa berdasarkan pencapaian</p>
    </div>

    <!-- Coming Soon Banner -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-trophy text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Segera Hadir!</h2>
                    <p class="text-green-100">Leaderboard sedang dalam pengembangan</p>
                </div>
            </div>
            <p class="text-green-100 mb-6">
                Bersaing dengan teman-temanmu! Lihat peringkat berdasarkan nilai, XP, dan achievement.
            </p>
            <a href="{{ route('siswa.dashboard') }}" class="px-6 py-3 bg-white text-green-600 rounded-2xl hover:bg-green-50 transition-colors font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
    </div>

    <!-- Preview Leaderboard (Dummy) -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Siswa</h3>
        <div class="space-y-3 opacity-50">
            @for($i = 1; $i <= 5; $i++)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-2xl">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-400">
                        {{ $i }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-700">Siswa {{ $i }}</p>
                        <p class="text-sm text-gray-500">Kelas X</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-700">0 XP</p>
                        <p class="text-xs text-gray-500">Level 1</p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
@endsection