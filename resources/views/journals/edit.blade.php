@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">

        <h1 class="text-xl font-bold mb-6">Edit Jurnal</h1>

        <form method="POST" action="{{ route('coachings.journals.update', [$coaching->id, $journal->id]) }}">
            @csrf
            @method('PUT')

            {{-- Tanggal --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $journal->tanggal) }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Catatan</label>
                <textarea name="catatan" rows="4" class="w-full border rounded px-3 py-2">{{ old('catatan', $journal->catatan) }}</textarea>
            </div>

            {{-- Refleksi --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Refleksi</label>
                <textarea name="refleksi" rows="3" class="w-full border rounded px-3 py-2">{{ old('refleksi', $journal->refleksi) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update
                </button>

                <a href="{{ route('coachings.show', $coaching) }}" class="px-4 py-2 bg-gray-200 rounded">
                    Batal
                </a>
            </div>
        </form>

    </div>
@endsection
