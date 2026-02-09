@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 bg-white shadow rounded-xl p-8">

        <h1 class="text-2xl font-bold mb-6 text-center">
            Laporan Coaching Siswa
        </h1>

        {{-- ================= IDENTITAS ================= --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-2">Informasi Coaching</h2>
            <div class="text-sm text-gray-700 space-y-1">
                <p><strong>Nama Siswa:</strong> {{ $coaching->student_name ?? '-' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($coaching->status) }}</p>
                <p><strong>Tanggal Mulai:</strong>
                    {{ \Carbon\Carbon::parse($coaching->start_date)->format('d M Y') }}
                </p>
            </div>
        </div>


        {{-- ================= RINGKASAN PROSES ================= --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3">Tahapan & Sesi Coaching</h2>

            @foreach ($coaching->sessions->groupBy('stage.name') as $stage => $sessions)
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700">{{ $stage }}</h3>
                    <ul class="list-disc ml-6 text-sm text-gray-600">
                        @foreach ($sessions as $session)
                            <li>
                                {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                                — {{ $session->notes }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>


        {{-- ================= JURNAL ================= --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3">Ringkasan Jurnal</h2>

            @foreach ($coaching->journals as $journal)
                <div class="mb-4 text-sm">
                    <p class="text-gray-500">
                        {{ \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') }}
                    </p>
                    <p class="mt-1">
                        {{ $journal->catatan }}
                    </p>

                    @if ($journal->refleksi)
                        <p class="mt-1 italic text-gray-600">
                            Refleksi: {{ $journal->refleksi }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>


        {{-- ================= PENUTUP ================= --}}
        <div class="mt-10 border-t pt-6 text-sm text-gray-700">
            <p>
                Coaching ini telah dilaksanakan sesuai tahapan yang direncanakan.
                Diharapkan siswa dapat melanjutkan perkembangan secara mandiri
                sesuai refleksi dan tindak lanjut yang telah dibuat.
            </p>
        </div>

    </div>
@endsection
