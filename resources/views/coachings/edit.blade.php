@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">

        <h1 class="text-2xl font-bold mb-6">Edit Coaching</h1>

        <form method="POST" action="{{ route('coachings.update', $coaching) }}" class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1">Tujuan Coaching</label>
                <input type="text" name="tujuan" value="{{ old('tujuan', $coaching->tujuan) }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full border rounded px-3 py-2">{{ old('deskripsi', $coaching->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="draft" @selected($coaching->status === 'draft')>
                        Draft
                    </option>
                    <option value="berjalan" @selected($coaching->status === 'berjalan')>
                        Berjalan
                    </option>
                    <option value="selesai" @selected($coaching->status === 'selesai')>
                        Selesai
                    </option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('coachings.show', $coaching) }}" class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
@endsection
