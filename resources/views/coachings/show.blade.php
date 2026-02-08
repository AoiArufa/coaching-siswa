@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-10 bg-white rounded-xl shadow p-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

            <div>
                <h2 class="text-2xl font-semibold">Jurnal Coaching</h2>
                <p class="text-sm text-gray-500">
                    Catatan perkembangan murid
                </p>
            </div>

            <div class="flex items-center gap-3">

                {{-- Status Badge --}}
                <span
                    class="px-3 py-1 text-xs rounded-full
                @if ($coaching->status === 'draft') bg-gray-200 text-gray-700
                @elseif($coaching->status === 'berjalan') bg-yellow-100 text-yellow-700
                @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst($coaching->status) }}
                </span>

                {{-- Action Buttons --}}
                @can('update', $coaching)
                    @if (Route::has('coachings.journals.create'))
                        <a href="{{ route('coachings.journals.create', $coaching) }}"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition">
                            + Tambah Jurnal
                        </a>
                    @endif
                @endcan

                @if (Route::has('coachings.journals.pdf'))
                    <a href="{{ route('coachings.journals.pdf', [
                        'coaching' => $coaching,
                        'from' => request('from'),
                        'to' => request('to'),
                    ]) }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition">
                        Export PDF
                    </a>
                @endif
            </div>
        </div>

        {{-- ================= FILTER ================= --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-6 items-end">

            <div>
                <label class="text-xs text-gray-500">Urutkan</label>
                <select name="sort" class="border rounded px-3 py-2 text-sm">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Dari</label>
                <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs text-gray-500">Sampai</label>
                <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2 text-sm">
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">
                Terapkan
            </button>

            @if (request()->hasAny(['sort', 'from', 'to']))
                <a href="{{ route('coachings.show', $coaching) }}" class="text-sm text-gray-600 hover:underline">
                    Reset
                </a>
            @endif
        </form>

        {{-- ================= PROGRESS ================= --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-6 border">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold text-gray-700 text-sm">
                    Progress Coaching
                </h3>
                <span class="text-sm font-medium text-blue-600">
                    {{ $progress }}%
                </span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%">
                </div>
            </div>
        </div>

        {{-- ================= SUMMARY ================= --}}
        <div class="grid md:grid-cols-5 gap-4 mb-6 text-sm">

            <div class="bg-gray-100 p-4 rounded">
                <p class="text-gray-500">Total Jurnal</p>
                <p class="text-lg font-semibold">{{ $summary['total'] }}</p>
            </div>

            <div class="bg-gray-100 p-4 rounded">
                <p class="text-gray-500">Awal</p>
                <p class="font-semibold">
                    {{ $summary['first_date'] ? \Carbon\Carbon::parse($summary['first_date'])->format('d M Y') : '-' }}
                </p>
            </div>

            <div class="bg-gray-100 p-4 rounded">
                <p class="text-gray-500">Terakhir</p>
                <p class="font-semibold">
                    {{ $summary['last_date'] ? \Carbon\Carbon::parse($summary['last_date'])->format('d M Y') : '-' }}
                </p>
            </div>

            <div class="bg-gray-100 p-4 rounded">
                <p class="text-gray-500">Jumlah Bulan Aktif</p>
                <p class="font-semibold">{{ $summary['months'] }}</p>
            </div>

            <div class="bg-gray-100 p-4 rounded">
                <p class="text-gray-500">Rata-rata / Bulan</p>
                <p class="font-semibold">{{ $summary['avg_per_month'] }}</p>
            </div>
        </div>

        {{-- ================= LIST ================= --}}
        @forelse ($journals as $journal)
            <div class="border rounded-lg p-4 mb-4 hover:shadow-sm transition">

                <p class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') }}
                </p>

                <p class="mt-2 whitespace-pre-line">
                    {{ $journal->catatan }}
                </p>

                @if ($journal->refleksi)
                    <div class="mt-3 text-sm bg-blue-50 p-3 rounded">
                        <strong>Refleksi:</strong><br>
                        {{ $journal->refleksi }}
                    </div>
                @endif

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
                Belum ada jurnal pada rentang ini.
            </div>
        @endforelse

        {{-- ================= PAGINATION ================= --}}
        <div class="mt-6">
            {{ $journals->links() }}
        </div>

    </div>
@endsection
