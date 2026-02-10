@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-xl font-bold mb-4">
            Refleksi Coaching - {{ $coaching->murid->name }}
        </h1>

        <form method="POST" action="{{ route('coachings.reflection.store', $coaching) }}" class="space-y-4">

            @csrf

            <div>
                <label class="block font-semibold">Hasil Perkembangan</label>
                <textarea name="hasil_perkembangan" class="w-full border p-2 rounded" required></textarea>
            </div>

            <div>
                <label class="block font-semibold">Kendala</label>
                <textarea name="kendala" class="w-full border p-2 rounded" required></textarea>
            </div>

            <div>
                <label class="block font-semibold">Evaluasi Metode</label>
                <textarea name="evaluasi_metode" class="w-full border p-2 rounded" required></textarea>
            </div>

            <div>
                <label class="block font-semibold">Catatan Guru (Opsional)</label>
                <textarea name="catatan_guru" class="w-full border p-2 rounded"></textarea>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan Refleksi
            </button>
        </form>
    </div>
@endsection
