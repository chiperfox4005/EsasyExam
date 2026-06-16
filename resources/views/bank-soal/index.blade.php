@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')

<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
{{-- HEADER --}}
<div class="flex items-center justify-between flex-wrap gap-4">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Bank Soal 📚
        </h1>

        <p class="text-gray-600 mt-1">
            Kelola kumpulan soal untuk ujian dan latihan
        </p>
    </div>

    <div class="flex gap-3 flex-wrap">

        <button
            onclick="document.getElementById('importModal').classList.remove('hidden')"
            class="px-4 py-2 bg-green-600 text-white rounded-3xl hover:bg-green-700 transition shadow-lg shadow-green-500/30"
        >
            <i class="fas fa-file-excel mr-2"></i>
            Import Excel
        </button>

        <a
            href="{{ route('bank-soal.template') }}"
            class="px-4 py-2 bg-amber-500 text-white rounded-3xl hover:bg-amber-600 transition shadow-lg"
        >
            <i class="fas fa-download mr-2"></i>
            Template
        </a>

        <a
            href="{{ route('bank-soal.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-3xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30"
        >
            <i class="fas fa-plus mr-2"></i>
            Tambah Soal
        </a>

    </div>

</div>


{{-- ALERT --}}
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-3xl flex items-center">
        <i class="fas fa-exclamation-circle mr-3"></i>
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-3xl flex items-center">
        <i class="fas fa-check-circle mr-3"></i>
        {{ session('success') }}
    </div>
@endif


{{-- STATS --}}
<div class="grid md:grid-cols-3 gap-5">

    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-database text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-2xl font-bold">
                    {{ $totalSoal }}
                </h3>

                <p class="text-gray-500">
                    Total Soal
                </p>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>

            <div>
                <h3 class="text-2xl font-bold">
                    {{ $soalPublished }}
                </h3>

                <p class="text-gray-500">
                    Published
                </p>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-save text-yellow-600"></i>
            </div>

            <div>
                <h3 class="text-2xl font-bold">
                    {{ $soalDraft }}
                </h3>

                <p class="text-gray-500">
                    Draft
                </p>
            </div>
        </div>
    </div>

</div>


{{-- FILTER --}}
<div class="bg-white rounded-3xl p-6 shadow-sm">

    <form method="GET" class="grid md:grid-cols-4 gap-4">

        <input
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari soal..."
            class="px-4 py-3 rounded-2xl border"
        >

        <select
            name="mapel_id"
            class="px-4 py-3 rounded-2xl border"
        >

            <option value="">
                Semua Mapel
            </option>

            @foreach($mapelList as $m)

                <option
                    value="{{ $m->id }}"
                    {{ request('mapel_id')==$m->id ? 'selected':'' }}
                >
                    {{ $m->nama }}
                </option>

            @endforeach

        </select>

        <select
            name="tipe"
            class="px-4 py-3 rounded-2xl border"
        >
            <option value="">Semua</option>
            <option value="pg">Pilihan Ganda</option>
            <option value="essay">Essay</option>
        </select>

        <button
            class="bg-blue-600 text-white rounded-2xl"
        >
            Filter
        </button>

    </form>

</div>


{{-- GRID --}}
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($soal as $s)

    <div class="bg-white rounded-3xl p-6 shadow-sm">

        <div class="mb-4">

            <h3 class="font-bold">
                {{ Str::limit($s->pertanyaan,90) }}
            </h3>

            <p class="text-sm text-gray-500 mt-2">
                {{ $s->mapel->nama }}
            </p>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('bank-soal.edit',$s->id) }}"
                class="flex-1 bg-blue-50 text-blue-600 py-2 rounded-2xl text-center"
            >
                Edit
            </a>

            <form
                action="{{ route('bank-soal.destroy',$s->id) }}"
                method="POST"
                class="flex-1"
            >

                @csrf
                @method('DELETE')

                <button
                    onclick="return confirm('Hapus soal?')"
                    class="w-full bg-red-50 text-red-600 py-2 rounded-2xl"
                >
                    Hapus
                </button>

            </form>

        </div>

    </div>

    @empty

    <div class="col-span-3 text-center py-20">

        <i class="fas fa-inbox text-6xl text-gray-300"></i>

        <p class="mt-4 text-gray-500">
            Belum ada soal
        </p>

    </div>

    @endforelse

</div>


<div>
    {{ $soal->links() }}
</div>

</div>

{{-- IMPORT MODAL --}}

<div
    id="importModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
>

<div class="bg-white rounded-3xl p-8 w-full max-w-lg">

    <div class="flex justify-between mb-6">

        <h2 class="text-2xl font-bold">
            Import Soal Excel
        </h2>

        <button
            onclick="document.getElementById('importModal').classList.add('hidden')"
        >
            ✕
        </button>

    </div>

    <p class="text-gray-600 text-sm mb-5">
        Upload file Excel (.xlsx/.xls/.csv) sesuai template.
    </p>

    <form
        action="{{ route('bank-soal.import') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <input
            type="file"
            name="file"
            required
            accept=".xlsx,.xls,.csv"
            class="w-full border rounded-2xl p-4"
        >

        <div class="mt-6 flex justify-end gap-3">

            <button
                type="button"
                onclick="document.getElementById('importModal').classList.add('hidden')"
                class="px-5 py-2 bg-gray-200 rounded-2xl"
            >
                Batal
            </button>

            <button
                class="px-5 py-2 bg-green-600 text-white rounded-2xl"
            >
                Import
            </button>

        </div>

    </form>

</div>


</div>

@endsection
