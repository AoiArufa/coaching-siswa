@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-sm text-gray-500">Total User</h2>
                <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-sm text-gray-500">Guru</h2>
                <p class="text-2xl font-bold">
                    {{ \App\Models\User::where('role', 'guru')->count() }}
                </p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-sm text-gray-500">Murid</h2>
                <p class="text-2xl font-bold">
                    {{ \App\Models\User::where('role', 'murid')->count() }}
                </p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-sm text-gray-500">Coaching</h2>
                <p class="text-2xl font-bold">
                    {{ \App\Models\Coaching::count() }}
                </p>
            </div>

        </div>

    </div>
@endsection
