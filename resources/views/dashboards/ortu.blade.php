@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">Dashboard Orang Tua</h1>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Laporan Anak</h2>

            <p class="text-gray-600">
                Silakan lihat perkembangan coaching anak Anda.
            </p>

            <a href="{{ route('journals.murid') }}" class="inline-block mt-3 text-blue-600">
                Lihat Jurnal →
            </a>
        </div>

    </div>
@endsection
