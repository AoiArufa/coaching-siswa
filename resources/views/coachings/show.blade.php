<!-- Header -->
<div class="bg-white p-6 rounded shadow">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold">{{ $coaching->tujuan }}</h1>

            <p class="text-gray-600 mt-1">
                Murid: <strong>{{ $coaching->murid->name }}</strong>
            </p>

            {{-- Badge Status --}}
            <div class="mt-2">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                    @if ($coaching->status === 'draft') bg-gray-200 text-gray-800
                    @elseif ($coaching->status === 'berjalan') bg-yellow-200 text-yellow-800
                    @else bg-green-200 text-green-800 @endif">
                    {{ ucfirst($coaching->status) }}
                </span>
            </div>
        </div>

        {{-- Tombol Edit (HANYA GURU PEMILIK) --}}
        @auth
            @if (auth()->user()->role === 'guru' && auth()->id() === $coaching->guru_id)
                <a href="{{ route('coachings.edit', $coaching) }}"
                    class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Edit
                </a>
            @endif
        @endauth
    </div>

    {{-- Deskripsi --}}
    @if ($coaching->deskripsi)
        <p class="mt-4 text-gray-700">{{ $coaching->deskripsi }}</p>
    @endif

</div>
