@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Jurnal Coaching
            </h1>

            <p class="text-gray-600">
                {{ $coaching->tujuan }}
            </p>
        </div>

        {{-- Tombol Tambah (HANYA MURID) --}}
        @auth
            @if (auth()->user()->role === 'murid')
                <form action="{{ route('murid.journals.store', $coaching) }}" method="POST">
                    @csrf

                    <div class="mb-2">
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <textarea name="catatan" class="form-control" placeholder="Catatan" required></textarea>
                    </div>

                    <div class="mb-2">
                        <textarea name="refleksi" class="form-control" placeholder="Refleksi (opsional)"></textarea>
                    </div>

                    <button class="btn btn-primary">Simpan Jurnal</button>
                </form>
            @endif
        @endauth

        {{-- Timeline --}}
        <div class="space-y-4">
            @forelse($journals as $journal)
                <div class="bg-white p-4 rounded shadow border-l-4 border-blue-600">

                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold">
                                {{ \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Ditulis oleh: {{ $journal->user->name ?? 'Murid' }}
                            </p>
                        </div>

                        {{-- Aksi Guru --}}
                        @if (auth()->user()->role === 'guru')
                            <form action="{{ route('journals.destroy', [$coaching, $journal]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 text-sm">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="mt-3">
                        <p class="text-gray-800">
                            {{ $journal->catatan }}
                        </p>

                        @if ($journal->refleksi)
                            <div class="mt-2 text-sm text-gray-600 italic">
                                “{{ $journal->refleksi }}”
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 bg-white p-6 rounded shadow">
                    Belum ada jurnal
                </div>
            @endforelse
        </div>

    </div>
@endsection
