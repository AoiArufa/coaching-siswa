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

            <div class="flex flex-wrap items-center gap-3">

                {{-- Status Badge --}}
                <span
                    class="px-3 py-1 text-xs rounded-full
                @if ($coaching->status === 'ongoing') bg-yellow-100 text-yellow-700
                @elseif($coaching->status === 'completed') bg-green-100 text-green-700
                @else bg-gray-200 text-gray-700 @endif">
                    {{ ucfirst($coaching->status) }}
                </span>

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

                {{-- Tambah Sesi (Terkunci jika completed) --}}
                @if ($coaching->status !== 'completed')
                    <a href="{{ route('sessions.create', $coaching) }}"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm transition">
                        + Tambah Sesi
                    </a>
                @endif

                {{-- Tambah Jurnal --}}
                @can('update', $coaching)
                    @if ($coaching->status !== 'completed')
                        <a href="{{ route('coachings.journals.create', $coaching) }}"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition">
                            + Tambah Jurnal
                        </a>
                    @endif
                @endcan

                {{-- ================= D.10.7 TOMBOL SELESAIKAN ================= --}}
                @if ($progress == 100 && $coaching->status !== 'completed')
                    <a href="{{ route('coachings.complete.form', $coaching) }}"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded text-sm transition">
                        Selesaikan Coaching
                    </a>
                @endif

                @if ($coaching->status === 'completed')
                    <a href="{{ route('coachings.report', $coaching) }}"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded text-sm transition">
                        Lihat Laporan
                    </a>
                @endif

            </div>
        </div>


        {{-- ================= PROGRESS ================= --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-8 border">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold text-sm text-gray-600">
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


        {{-- ================= SESSIONS PER STAGE ================= --}}
        <div class="mb-10">
            <h3 class="text-lg font-semibold mb-4">Tahapan & Sesi Coaching</h3>

            @foreach ($coaching->sessions->groupBy('stage.name') as $stage => $sessions)
                <div class="mb-6 border rounded-lg p-4 bg-gray-50">

                    <h4 class="font-semibold text-gray-700 mb-3">
                        {{ $stage }}
                    </h4>

                    @foreach ($sessions as $session)
                        <div class="mb-3 p-3 bg-white rounded shadow-sm text-sm">
                            <p class="text-gray-500 text-xs">
                                {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                            </p>
                            <p class="mt-1 whitespace-pre-line">
                                {{ $session->notes }}
                            </p>
                        </div>
                    @endforeach

                </div>
            @endforeach
        </div>


        {{-- ================= FILTER ================= --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-6 items-end">

            <div>
                <label class="text-xs text-gray-500">Urutkan</label>
                <select name="sort" class="border rounded px-3 py-2 text-sm">
                    <option value="latest" @selected(request('sort', 'latest') == 'latest')>Terbaru</option>
                    <option value="oldest" @selected(request('sort') == 'oldest')>Terlama</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Dari</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="border rounded px-3 py-2 text-sm">
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


        {{-- ================= LIST JURNAL ================= --}}
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
                    @if ($coaching->status !== 'completed')
                        <div class="mt-3 flex gap-4 text-sm">

                            <a href="{{ route('coachings.journals.edit', [$coaching, $journal]) }}"
                                class="text-blue-600 hover:underline">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('coachings.journals.destroy', [$coaching, $journal]) }}"
                                onsubmit="return confirm('Hapus jurnal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    @endif
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
