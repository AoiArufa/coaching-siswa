@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Jurnal Saya</h1>

    @forelse ($journals as $journal)
        <div class="bg-white p-4 mb-4 rounded shadow">
            <div class="text-sm text-gray-500">
                {{ $journal->tanggal }}
            </div>

            <p class="mt-2">{{ $journal->catatan }}</p>

            @if ($journal->refleksi)
                <div class="mt-2 text-gray-600 italic">
                    Refleksi: {{ $journal->refleksi }}
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-500">Belum ada jurnal.</p>
    @endforelse
</div>
@endsection
