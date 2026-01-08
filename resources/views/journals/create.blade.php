@extends('layouts.app')

@section('content')
    <h2>Tambah Jurnal</h2>

    <form method="POST" action="{{ route('coachings.journals.store', $coaching) }}">
        @csrf

        <textarea name="catatan" placeholder="Catatan coaching"></textarea>
        <textarea name="refleksi" placeholder="Refleksi"></textarea>

        <button type="submit">Simpan</button>
    </form>
@endsection
