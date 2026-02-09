@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-xl font-semibold mb-6">
            Tambah Sesi Coaching
        </h2>

        @if (session('error'))
            <div class="mb-4 text-red-600 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('sessions.store', $coaching) }}">
            @csrf

            {{-- Tahap --}}
            <div class="mb-4">
                <label class="text-sm text-gray-600">Tahap</label>
                <select name="coaching_stage_id" class="w-full border rounded px-3 py-2 mt-1">

                    @foreach ($allStages as $stage)
                        <option value="{{ $stage->id }}" @if ($nextStage && $stage->id != $nextStage->id) disabled @endif>
                            {{ $stage->name }}
                            @if ($nextStage && $stage->id != $nextStage->id)
                                (Terkunci)
                            @endif
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Tanggal --}}
            <div class="mb-4">
                <label class="text-sm text-gray-600">Tanggal</label>
                <input type="date" name="session_date" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="text-sm text-gray-600">Catatan</label>
                <textarea name="notes" class="w-full border rounded px-3 py-2 mt-1" rows="4"></textarea>
            </div>

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Simpan
            </button>

        </form>

    </div>
@endsection
