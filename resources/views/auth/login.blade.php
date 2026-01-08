@extends('layouts.guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-blue-900">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

            <div class="text-center mb-6">
                <img src="{{ asset('logo.png') }}" class="w-20 mx-auto mb-2">
                <h1 class="text-xl font-bold text-blue-900">
                    Sistem Coaching Siswa
                </h1>
                <p class="text-sm text-gray-500">
                    Sekolah / Pondok Pesantren
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <x-text-input name="email" type="email" placeholder="Email" required autofocus />
                <x-text-input name="password" type="password" placeholder="Password" required class="mt-3" />

                <x-primary-button class="w-full mt-4 bg-blue-800">
                    Masuk
                </x-primary-button>
            </form>

            <p class="text-center text-sm mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-700 font-semibold">
                    Daftar
                </a>
            </p>
        </div>
    </div>
@endsection
