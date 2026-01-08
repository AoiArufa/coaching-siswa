@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Dashboard Guru
            </h1>

            <a href="{{ route('coachings.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Tambah Coaching
            </a>
        </div>

        <!-- Content -->
        @if ($coachings->isEmpty())
            <!-- EMPTY STATE -->
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <h2 class="text-lg font-semibold text-gray-700">
                    Belum ada coaching
                </h2>
                <p class="text-gray-500 mt-2">
                    Mulai dengan membuat coaching pertama Anda.
                </p>

                <a href="{{ route('coachings.create') }}"
                    class="inline-block mt-4 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Buat Coaching
                </a>
            </div>
        @else
            <!-- LIST COACHING -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($coachings as $coaching)
                    <div class="bg-white rounded-lg shadow p-5">
                        <h3 class="font-semibold text-gray-800">
                            {{ $coaching->tujuan }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Status:
                            <span class="font-medium">
                                {{ ucfirst($coaching->status) }}
                            </span>
                        </p>

                        <p class="text-gray-600 text-sm mt-3">
                            {{ Str::limit($coaching->deskripsi, 80) }}
                        </p>

                        <div class="mt-4 flex justify-end gap-2">
                            <a href="{{ route('coachings.show', $coaching) }}"
                                class="text-sm text-blue-600 hover:underline">
                                Detail
                            </a>
                            <a href="{{ route('coachings.edit', $coaching) }}"
                                class="text-sm text-gray-600 hover:underline">
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
