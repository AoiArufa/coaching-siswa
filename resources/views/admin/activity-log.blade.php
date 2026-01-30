@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white rounded-xl shadow">

        <h1 class="text-2xl font-semibold mb-6">Activity Log</h1>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Objek</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr class="border-t">
                        <td class="p-2 text-sm text-gray-500">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </td>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>

    </div>
@endsection
