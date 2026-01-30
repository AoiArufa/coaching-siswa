@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-4">Tambah Jurnal</h1>

    <form action="{{ route('coachings.journals.store', $coaching) }}" method="POST">
        @csrf

        <div class="mb-2">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="border p-2 w-full" required>
        </div>

        <div class="mb-2">
            <label>Catatan</label>
            <textarea name="catatan" class="border p-2 w-full" required></textarea>
        </div>

        <div class="mb-2">
            <label>Refleksi</label>
            <textarea name="refleksi" class="border p-2 w-full"></textarea>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
@endsection
