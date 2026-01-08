@extends('layouts.app')

@section('content')
    <div class="text-center py-20">
        <h1 class="text-4xl font-bold text-red-600">403</h1>
        <p class="mt-4 text-gray-600">
            Anda tidak memiliki akses ke halaman ini.
        </p>

        <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-4 py-2 bg-blue-600 text-white rounded">
            Kembali ke Dashboard
        </a>
    </div>
@endsection
