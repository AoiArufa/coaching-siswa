@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-xl font-semibold mb-6">
            Evaluasi Akhir Coaching
        </h2>

        <form method="POST" action="{{ route('coachings.complete', $coaching) }}">
            @csrf

            <div class="mb-4">
                <label class="text-sm text-gray-600">
                    Ringkasan Evaluasi
                </label>

                <textarea name="final_evaluation" rows="6" class="w-full border rounded px-3 py-2 mt-1" required></textarea>
            </div>

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Selesaikan Coaching
            </button>

        </form>

    </div>
@endsection
