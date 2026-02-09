@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-4">Tambah Jurnal</h1>

    <form action="{{ route('coachings.journals.store', $coaching) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">
                Tanggal
            </label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $journal->tanggal ?? '') }}"
                class="mt-1 block w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">
                Catatan
            </label>
            <textarea name="catatan" rows="4" class="mt-1 block w-full border rounded px-3 py-2" required>{{ old('catatan', $journal->catatan ?? '') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">
                Refleksi
            </label>
            <textarea name="refleksi" rows="3" class="mt-1 block w-full border rounded px-3 py-2">{{ old('refleksi', $journal->refleksi ?? '') }}</textarea>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
@endsection
