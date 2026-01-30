@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-xl font-semibold mb-4">
            Grafik Perkembangan Jurnal
        </h2>

        <p class="text-sm text-gray-500 mb-6">
            Coaching: {{ $coaching->murid->name }}
        </p>

        <canvas id="journalChart"></canvas>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($data->keys()->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('M'))) !!};
        const values = {!! json_encode($data->values()) !!};

        new Chart(document.getElementById('journalChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Jurnal',
                    data: values,
                    borderWidth: 2,
                    tension: 0.3
                }]
            }
        });
    </script>
@endsection
