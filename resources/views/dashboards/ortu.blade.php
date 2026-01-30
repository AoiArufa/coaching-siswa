@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard Orang Tua</h1>

    {{-- Statistik --}}
    <div class="p-4 bg-purple-100 rounded-lg w-64 mb-6">
        <h2 class="text-sm text-gray-600">Total Jurnal Anak</h2>
        <p class="text-3xl font-bold text-purple-700">
            {{ $totalJournals }}
        </p>
    </div>

    {{-- Notifikasi --}}
    <h2 class="text-lg font-semibold mb-3">Notifikasi Terbaru</h2>

    @forelse ($notifications as $notif)
        <div class="p-3 mb-2 bg-blue-50 border-l-4 border-blue-500 rounded flex justify-between items-center">

            <span>
                {{ $notif->data['message'] ?? 'Notifikasi baru' }}
            </span>

            <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                @csrf
                <button class="text-xs text-blue-600 hover:underline">
                    Tandai dibaca
                </button>
            </form>

        </div>
    @empty
        <p class="text-gray-500 text-sm">
            Tidak ada notifikasi baru
        </p>
    @endforelse
@endsection
