@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-10 bg-white rounded-xl shadow p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold">Jurnal Coaching</h2>
                <p class="text-sm text-gray-500">Catatan perkembangan murid</p>
            </div>

            <div class="flex gap-2">
                @if (Route::has('coachings.journals.pdf'))
                    <a href="{{ route('coachings.journals.pdf', [
                        'coaching' => $coaching,
                        'from' => request('from'),
                        'to' => request('to'),
                    ]) }}"
                        class="bg-red-600 text-white px-4 py-2 rounded text-sm">
                        Export PDF
                    </a>
                @endif

                @can('update', $coaching)
                    @if (Route::has('coachings.journals.create'))
                        <a href="{{ route('coachings.journals.create', $coaching) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                            + Tambah Jurnal
                        </a>
                    @endif
                @endcan
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-6">
            <select name="sort" class="border rounded px-3 py-2 text-sm">
                <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
            </select>

            <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2 text-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2 text-sm">

            <button class="bg-blue-600 text-white px-4 rounded text-sm">
                Terapkan
            </button>
        </form>

        {{-- SUMMARY --}}
        <div class="bg-gray-100 p-4 rounded mb-6 text-sm">
            <strong>Total Jurnal:</strong> {{ $journals->total() }}
        </div>

        {{-- LIST --}}
        @forelse ($journals as $journal)
            <div class="border rounded p-4 mb-4">
                <p class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') }}
                </p>

                <p class="mt-2 whitespace-pre-line">
                    {{ $journal->catatan }}
                </p>

                @can('update', $journal)
                    <div class="mt-3 flex gap-4 text-sm">
                        @if (Route::has('coachings.journals.edit'))
                            <a href="{{ route('coachings.journals.edit', [$coaching, $journal]) }}"
                                class="text-blue-600 hover:underline">
                                Edit
                            </a>
                        @endif

                        @if (Route::has('coachings.journals.destroy'))
                            <form method="POST" action="{{ route('coachings.journals.destroy', [$coaching, $journal]) }}"
                                onsubmit="return confirm('Hapus jurnal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                @endcan
            </div>
        @empty
            <div class="text-center text-gray-500 py-10">
                Belum ada jurnal.
            </div>
        @endforelse

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $journals->withQueryString()->links() }}
        </div>

    </div>
@endsection
