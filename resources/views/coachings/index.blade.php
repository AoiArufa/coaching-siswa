{{-- @extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Daftar Coaching</h1>

        <a href="{{ route('coachings.create') }}" class="bg-blue-700 text-white px-4 py-2 rounded">
            + Coaching Baru
        </a>

        <table class="w-full mt-6 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Murid</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coachings as $c)
                    <tr class="border-t">
                        <td class="p-2">{{ $c->murid->name }}</td>
                        <td>{{ $c->tujuan }}</td>
                        <td>{{ ucfirst($c->status) }}</td>
                        <td>
                            <a href="{{ route('coachings.edit', $c) }}" class="text-blue-600">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection --}}
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
