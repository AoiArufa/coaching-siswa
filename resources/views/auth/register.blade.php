@extends('layouts.guest')

@section('content')
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow">

        <h2 class="text-xl font-bold text-center mb-6">
            Registrasi Akun
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Nama</label>
                <input type="text" name="name" class="w-full border rounded p-2" required autofocus>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
            </div>

            <button class="w-full bg-blue-700 text-white py-2 rounded">
                Daftar
            </button>

            <p class="text-center text-sm mt-4">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600">Login</a>
            </p>
        </form>
    </div>
@endsection
