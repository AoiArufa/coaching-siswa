@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-10">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold">Analytics Coaching</h1>
            <p class="text-gray-500 text-sm">
                Ringkasan performa coaching Anda
            </p>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total Coaching</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalCoachings }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total Jurnal</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalJournals }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">Coaching Selesai</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $completedCoachings }}
                </h2>
            </div>

        </div>

        {{-- CHART --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Aktivitas Jurnal per Bulan</h3>

            <canvas id="journalChart" height="100"></canvas>
        </div>

    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('journalChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->keys()) !!},
                datasets: [{
                    label: 'Jumlah Jurnal',
                    data: {!! json_encode($chartData->values()) !!},
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
@endsection
