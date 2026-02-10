@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-xl font-bold mb-4">
            Rencana Tindak Lanjut - {{ $coaching->murid->name }}
        </h1>

        <form method="POST" action="{{ route('coachings.followups.store', $coaching) }}" class="space-y-4">

            @csrf

            <div>
                <label class="block font-semibold">Judul</label>
                <input type="text" name="judul" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border p-2 rounded" required></textarea>
            </div>

            <div>
                <label class="block font-semibold">Target Tanggal</label>
                <input type="date" name="target_tanggal" class="w-full border p-2 rounded">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan RTL
            </button>
        </form>
    </div>
@endsection
