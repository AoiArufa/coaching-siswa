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

    <ul>
        @foreach ($coachings as $coaching)
            <li>
                {{ $coaching->murid->name }} - {{ $coaching->tujuan }}
            </li>
        @endforeach
    </ul>
@endsection
