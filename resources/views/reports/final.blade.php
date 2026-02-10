@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">
            Laporan Coaching
        </h1>

        {{-- INFORMASI DASAR --}}
        <div class="border p-4 rounded">
            <p><strong>Murid:</strong> {{ $coaching->murid->name }}</p>
            <p><strong>Guru:</strong> {{ $coaching->guru->name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($coaching->status) }}</p>
            <p><strong>Total Jurnal:</strong> {{ $totalJournals }}</p>
        </div>

        {{-- TUJUAN --}}
        <div class="border p-4 rounded">
            <h2 class="font-bold mb-2">Tujuan Coaching</h2>
            <p>{{ $coaching->tujuan }}</p>
        </div>

        {{-- REFLEKSI --}}
        @if ($coaching->refleksi)
            <div class="border p-4 rounded">
                <h2 class="font-bold mb-2">Refleksi Akhir</h2>
                <p>{{ $coaching->refleksi }}</p>
            </div>
        @endif

        {{-- JURNAL --}}
        <div class="border p-4 rounded">
            <h2 class="font-bold mb-2">Ringkasan Jurnal</h2>

            @foreach ($coaching->journals as $journal)
                <div class="mb-3 border-b pb-2">
                    <div class="text-sm text-gray-500">
                        {{ $journal->tanggal }} - {{ $journal->user->name }}
                    </div>
                    <div>{{ $journal->catatan }}</div>
                </div>
            @endforeach
        </div>

        {{-- RTL --}}
        @if ($coaching->followUps->count())
            <div class="border p-4 rounded">
                <h2 class="font-bold mb-2">Rencana Tindak Lanjut</h2>

                @foreach ($coaching->followUps as $rtl)
                    <div class="mb-2">
                        <strong>{{ $rtl->judul }}</strong>
                        <div>{{ $rtl->deskripsi }}</div>
                        <div class="text-sm text-gray-500">
                            Target: {{ $rtl->target_tanggal ?? '-' }} |
                            Status: {{ ucfirst($rtl->status) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
