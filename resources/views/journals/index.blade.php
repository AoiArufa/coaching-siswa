@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10">

        <div class="bg-white rounded-2xl shadow p-8">

            {{-- ================= HEADER ================= --}}
            <div class="mb-8">
                <h1 class="text-2xl font-semibold">
                    Jurnal Coaching
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $coaching->tujuan }}
                </p>
            </div>


            {{-- ================= FORM TAMBAH (MURID) ================= --}}
            @auth
                @if (auth()->user()->role === 'murid')
                    <div class="mb-10 border rounded-xl p-6 bg-gray-50">
                        <h3 class="text-sm font-medium mb-4 text-gray-700">
                            Tambah Jurnal
                        </h3>

                        <form action="{{ route('murid.journals.store', $coaching) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Tanggal
                                </label>
                                <input type="date" name="tanggal" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" rows="3" required placeholder="Tulis catatan perkembangan..."
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Refleksi (Opsional)
                                </label>
                                <textarea name="refleksi" rows="2" placeholder="Refleksi pribadi..."
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"></textarea>
                            </div>

                            <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                Simpan Jurnal
                            </button>
                        </form>
                    </div>
                @endif
            @endauth



            {{-- ================= TIMELINE ================= --}}
            <div class="space-y-6">

                @forelse($journals as $journal)
                    <div class="border-l-4 border-blue-600 bg-white p-5 rounded-xl shadow-sm hover:shadow transition">

                        <div class="flex justify-between items-start">

                            <div>
                                <p class="font-semibold text-sm">
                                    {{ \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    Ditulis oleh:
                                    {{ optional($journal->user)->name ?? 'Murid' }}
                                </p>
                            </div>

                            {{-- Aksi Guru --}}
                            @auth
                                @if (auth()->user()->role === 'guru')
                                    <form action="{{ route('journals.destroy', [$coaching, $journal]) }}" method="POST"
                                        onsubmit="return confirm('Hapus jurnal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 text-xs hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            @endauth

                        </div>

                        <div class="mt-4 text-sm text-gray-800 whitespace-pre-line">
                            {{ $journal->catatan }}
                        </div>

                        @if ($journal->refleksi)
                            <div class="mt-3 bg-gray-50 p-3 rounded text-sm italic text-gray-600">
                                “{{ $journal->refleksi }}”
                            </div>
                        @endif

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-10 border rounded-xl">
                        Belum ada jurnal.
                    </div>
                @endforelse

            </div>

        </div>
    </div>
@endsection
