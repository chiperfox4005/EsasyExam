@extends('layouts.app')

@section('title', 'Test Ujian')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-green-600 mb-6">✅ TEST BERHASIL!</h1>
    
    <div class="bg-white rounded-3xl p-6 shadow">
        <p class="text-gray-600">Jika kamu melihat ini, berarti:</p>
        <ul class="list-disc list-inside mt-4 space-y-2 text-gray-700">
            <li>✅ Layout berfungsi</li>
            <li>✅ Route berfungsi</li>
            <li>✅ Controller berfungsi</li>
            <li>❌ Masalah ada di Livewire component</li>
        </ul>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-2xl border border-blue-200">
            <p class="text-sm text-blue-900">
                <strong>Next step:</strong> Kita perlu fix Livewire component atau gunakan controller biasa.
            </p>
        </div>
    </div>
</div>
@endsection