@extends('layouts.app')

@section('content')
    <section class="bg-blue-900 text-white py-20 text-center">
        <h1 class="text-4xl font-bold mb-4">
            Sistem Program Coaching Siswa
        </h1>
        <p class="max-w-2xl mx-auto">
            Membangun karakter, prestasi, dan potensi siswa secara terarah
        </p>

        <div class="mt-6">
            <a href="{{ route('login') }}" class="bg-white text-blue-900 px-6 py-3 rounded font-semibold">
                Login Sistem
            </a>
        </div>
    </section>

    <section class="py-16 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach (['Visi & Misi', 'Jurnal Coaching', 'Refleksi & RTL', 'Pelaporan Siswa', 'Pelaporan Orang Tua', 'Monitoring Sekolah'] as $item)
            <div class="border rounded-lg p-6 text-center shadow">
                <h3 class="font-bold text-lg">{{ $item }}</h3>
            </div>
        @endforeach
    </section>
@endsection
