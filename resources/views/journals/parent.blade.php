@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">

        <h1 class="text-xl font-bold mb-4">Jurnal Anak</h1>

        <table class="w-full border">
            <tr class="bg-gray-100">
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Anak</th>
                <th class="border p-2">Catatan</th>
            </tr>

            @foreach ($journals as $journal)
                <tr>
                    <td class="border p-2">{{ $journal->created_at->format('d M Y') }}</td>
                    <td class="border p-2">{{ $journal->murid->name }}</td>
                    <td class="border p-2">{{ $journal->catatan }}</td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
