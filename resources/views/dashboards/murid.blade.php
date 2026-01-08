@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">Dashboard Murid</h1>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Coaching Saya</h2>

            <ul>
                @forelse(auth()->user()->coachingsAsMurid as $coaching)
                    <li class="border-b py-2">
                        {{ $coaching->tujuan }}
                        <span class="text-sm text-gray-500">
                            ({{ $coaching->status }})
                        </span>
                    </li>
                @empty
                    <li>Belum ada coaching</li>
                @endforelse
            </ul>

            <a href="{{ route('journals.murid') }}" class="inline-block mt-3 text-blue-600">
                Lihat Jurnal Saya →
            </a>
        </div>

    </div>
@endsection
