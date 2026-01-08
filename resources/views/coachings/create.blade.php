@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">

        <h1 class="text-2xl font-bold mb-6">Tambah Coaching</h1>

        <form method="POST" action="{{ route('coachings.store') }}" class="bg-white p-6 rounded shadow space-y-4">
            @csrf

            <!-- Murid -->
            <div>
                <label class="block font-medium mb-1">Murid</label>
                <select name="murid_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Murid --</option>
                    @foreach ($murids as $murid)
                        <option value="{{ $murid->id }}">
                            {{ $murid->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tujuan -->
            <div>
                <label class="block font-medium mb-1">Tujuan Coaching</label>
                <input type="text" name="tujuan" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded px-3 py-2" rows="4"></textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-medium mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="draft">Draft</option>
                    <option value="berjalan">Berjalan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('guru.dashboard') }}" class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>
    </div>
@endsection
