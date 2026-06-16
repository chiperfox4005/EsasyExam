@extends('layouts.app')

@section('title', 'AI Mentor')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">AI Mentor 🤖</h1>
        <p class="text-gray-600 mt-1">Asisten AI untuk membantumu belajar</p>
    </div>

    <!-- Coming Soon Banner -->
    <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-robot text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Segera Hadir!</h2>
                    <p class="text-yellow-100">AI Mentor sedang dalam pengembangan</p>
                </div>
            </div>
            <p class="text-yellow-100 mb-6">
                Tanya apapun tentang pelajaranmu! AI Mentor akan membantumu memahami materi, mengerjakan soal, dan belajar lebih efektif.
            </p>
            <a href="{{ route('siswa.dashboard') }}" class="px-6 py-3 bg-white text-orange-600 rounded-2xl hover:bg-orange-50 transition-colors font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -translate-y-32 translate-x-32"></div>
    </div>

    <!-- Preview Chat (Dummy) -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Chat</h3>
        <div class="space-y-4 opacity-50">
            <div class="flex gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0"></div>
                <div class="flex-1 bg-gray-100 rounded-2xl p-3">
                    <p class="text-sm text-gray-600">Halo! Saya AI Mentor. Ada yang bisa saya bantu?</p>
                </div>
            </div>
            <div class="flex gap-3 flex-row-reverse">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0"></div>
                <div class="flex-1 bg-blue-100 rounded-2xl p-3">
                    <p class="text-sm text-gray-600">Fitur ini akan segera tersedia...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection