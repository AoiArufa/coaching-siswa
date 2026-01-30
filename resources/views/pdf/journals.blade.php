<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Coaching</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin: 0;
        }

        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 10px 0 15px 0;
        }

        .meta {
            font-size: 11px;
            color: #555;
            margin-bottom: 20px;
        }

        .journal {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .date {
            font-size: 11px;
            color: #777;
            margin-bottom: 4px;
        }

        .signature {
            margin-top: 50px;
        }

        .signature table {
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .signature .name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="15%" valign="middle">
                <img src="{{ public_path('images/logo.png') }}" width="80">
            </td>
            <td width="85%" valign="middle">
                <h2>LAPORAN JURNAL COACHING SISWA</h2>
                <div style="font-size:11px; color:#555;">
                    Program Coaching Siswa
                </div>
            </td>
        </tr>
    </table>

    <hr>

    {{-- META --}}
    <div class="meta">
        <strong>Judul Coaching:</strong> {{ $coaching->judul ?? '-' }} <br>
        <strong>Periode:</strong>
        {{ $from ? \Carbon\Carbon::parse($from)->format('d M Y') : 'Awal' }}
        –
        {{ $to ? \Carbon\Carbon::parse($to)->format('d M Y') : 'Sekarang' }} <br>
        <strong>Total Jurnal:</strong> {{ $journals->count() }}
    </div>

    {{-- ISI JURNAL --}}
    @forelse ($journals as $journal)
        <div class="journal">
            <div class="date">
                {{ \Carbon\Carbon::parse($journal->tanggal)->format('d M Y') }}
            </div>

            <div>
                {{ $journal->catatan }}
            </div>

            @if ($journal->refleksi)
                <div style="margin-top:6px; font-style:italic; font-size:11px;">
                    <strong>Refleksi:</strong> {{ $journal->refleksi }}
                </div>
            @endif
        </div>
    @empty
        <p style="text-align:center; color:#777;">
            Tidak ada jurnal coaching.
        </p>
    @endforelse

    {{-- TANDA TANGAN --}}
    <div class="signature">
        <table>
            <tr>
                <td width="50%">
                    Mengetahui,<br>
                    Guru / Coach
                    <div class="name">
                        {{ $coachName ?? '____________________' }}
                    </div>
                </td>
                <td width="50%">
                    Mengetahui,<br>
                    Kepala Sekolah / Pimpinan Pondok
                    <div class="name">
                        {{ $headmasterName ?? '____________________' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
