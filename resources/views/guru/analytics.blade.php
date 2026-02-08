@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-10">

        <h1 class="text-2xl font-bold mb-6">
            Analytics Coaching
        </h1>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total Coaching</p>
                <p class="text-3xl font-bold mt-2">
                    {{ $totalCoachings }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total Jurnal</p>
                <p class="text-3xl font-bold mt-2">
                    {{ $totalJournals }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Rata-rata per Coaching</p>
                <p class="text-3xl font-bold mt-2">
                    {{ $avgPerCoaching }}
                </p>
            </div>

        </div>

        {{-- CHART --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="font-semibold mb-4">Aktivitas Jurnal per Bulan</h2>

            <canvas id="journalChart"></canvas>
        </div>

    </div>

    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('journalChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->keys()) !!},
                datasets: [{
                    label: 'Jumlah Jurnal',
                    data: {!! json_encode($chartData->values()) !!},
                    borderWidth: 2,
                    tension: 0.3
                }]
            }
        });
    </script>
@endsection
