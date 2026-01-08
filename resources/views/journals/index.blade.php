@extends('layouts.app')

@section('content')
    <h2>Jurnal Coaching: {{ $coaching->tujuan }}</h2>

    <a href="{{ route('coachings.journals.create', $coaching) }}">
        + Tambah Jurnal
    </a>

    <ul>
        @foreach ($journals as $journal)
            <li>
                <strong>{{ $journal->created_at->format('d M Y') }}</strong><br>
                {{ $journal->catatan }}
            </li>
        @endforeach
    </ul>
@endsection
