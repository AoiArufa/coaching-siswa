@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold">Data Coaching</h1>

    <a href="{{ route('coachings.create') }}">+ Tambah Coaching</a>

    <ul class="mt-4 space-y-3">
        @foreach ($coachings as $coaching)
            <li class="border p-3 rounded flex justify-between items-center">
                <div>
                    <div class="font-semibold">
                        {{ $coaching->murid->name }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $coaching->tujuan }}
                    </div>
                </div>

                {{-- 🔐 C.5.1 — AUTHORIZED ACTION --}}
                <div class="space-x-2">
                    @can('view', $coaching)
                        <a href="{{ route('coachings.show', $coaching) }}" class="text-gray-600 hover:underline">
                            Lihat
                        </a>
                    @endcan

                    @can('update', $coaching)
                        <a href="{{ route('coachings.edit', $coaching) }}" class="text-blue-600 hover:underline">
                            Edit
                        </a>
                    @endcan

                    @can('delete', $coaching)
                        <form method="POST" action="{{ route('coachings.destroy', $coaching) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Hapus
                            </button>
                        </form>
                    @endcan
                </div>
            </li>
        @endforeach
    </ul>
@endsection
