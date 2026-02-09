@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto mt-10">

        <h1 class="text-xl font-bold mb-4">
            Bahan Ajar - {{ $coaching->murid->name }}
        </h1>

        @can('update', $coaching)
            <a href="{{ route('guru.coachings.materials.create', $coaching) }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Bahan Ajar
            </a>
        @endcan

        <div class="mt-6 space-y-4">
            @foreach ($materials as $material)
                <div class="border p-4 rounded">
                    <h3 class="font-semibold">{{ $material->title }}</h3>

                    <p class="text-sm text-gray-600">
                        {{ $material->description }}
                    </p>

                    @if ($material->file_path)
                        <a href="{{ asset('storage/' . $material->file_path) }}" class="text-blue-600 text-sm">
                            Download File
                        </a>
                    @endif

                    @if ($material->external_link)
                        <a href="{{ $material->external_link }}" target="_blank" class="text-green-600 text-sm">
                            Buka Link
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
@endsection
