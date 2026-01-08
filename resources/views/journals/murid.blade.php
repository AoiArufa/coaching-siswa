@extends('layouts.app')

@section('content')
    <h2>Jurnal Coaching Saya</h2>

    @foreach ($journals as $journal)
        <div>
            <strong>{{ $journal->coaching->tujuan }}</strong><br>
            {{ $journal->catatan }}
        </div>
    @endforeach
@endsection
