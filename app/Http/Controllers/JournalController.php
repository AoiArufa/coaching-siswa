<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coaching;
use App\Models\Journal;
use App\Notifications\JournalCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // Guru melihat jurnal coaching tertentu
    // public function index(Coaching $coaching)
    // {
    //     abort_if(
    //         auth()->id() !== $coaching->murid_id &&
    //             auth()->id() !== $coaching->guru_id &&
    //             auth()->id() !== optional($coaching->murid)->parent_id,
    //         403
    //     );

    //     $journals = $coaching->journals()
    //         ->with('user')
    //         ->orderBy('tanggal', 'desc')
    //         ->get();

    //     return view('journals.index', compact('coaching', 'journals'));
    // }
    public function index(Request $request, Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $from = $request->query('from');
        $to   = $request->query('to');

        $journals = $coaching->journals()
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('tanggal', [$from, $to]);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        // 🔹 Ringkasan (C.19.2)
        $summary = [
            'total' => $journals->total(),
            'from'  => $journals->min('tanggal'),
            'to'    => $journals->max('tanggal'),
        ];

        return view('journals.index', compact(
            'coaching',
            'journals',
            'summary',
            'from',
            'to'
        ));
    }

    public function create(Coaching $coaching)
    {
        // hanya guru pemilik
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );

        return view('journals.create', compact('coaching'));
    }

    public function edit(Coaching $coaching, Journal $journal)
    {
        // 🔐 hanya guru pemilik coaching
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );

        return view('journals.edit', compact('coaching', 'journal'));
    }

    // public function store(Request $request, Coaching $coaching)
    // {
    //     abort_if(
    //         auth()->user()->role !== 'guru' ||
    //             $coaching->guru_id !== auth()->id(),
    //         403
    //     );

    //     $validated = $request->validate([
    //         'tanggal'  => 'required|date',
    //         'catatan'  => 'required|string',
    //         'refleksi' => 'nullable|string',
    //     ]);

    //     $journal = $coaching->journals()->create($validated);

    //     // 🔔 C.3.2 — ACTIVITY LOG (CREATE)
    //     activity_log(
    //         'create',
    //         $journal,
    //         'Menambahkan jurnal coaching'
    //     );

    //     return redirect()
    //         ->route('coachings.show', $coaching)
    //         ->with('success', 'Jurnal berhasil ditambahkan');
    // }

    /**
     * ✍️ STORE JURNAL (MURID SAJA)
     */

    public function store(Request $request, Coaching $coaching)
    {
        // 🔐 HANYA GURU PEMILIK
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );

        $validated = $request->validate([
            'tanggal'  => 'required|date',
            'catatan'  => 'required|string',
            'refleksi' => 'nullable|string',
        ]);

        // 1️⃣ Simpan jurnal
        $journal = $coaching->journals()->create($validated);

        // 2️⃣ Activity log
        activity_log('create', $journal, 'Menambahkan jurnal coaching');

        // 3️⃣ 🔔 NOTIFIKASI KE ORANG TUA (C.3.3)
        $parent = optional($coaching->murid)->parent;

        if ($parent) {
            $parent->notify(new JournalCreated($journal));
        }

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Jurnal berhasil ditambahkan');
    }

    // public function show(Journal $journal)
    // {
    //     $this->authorize('view', $journal);

    //     return view('journals.show', compact('journal'));
    // }
    public function show(Coaching $coaching)
    {
        $journals = $coaching->journals()
            ->when(
                request('from'),
                fn($q) =>
                $q->whereDate('tanggal', '>=', request('from'))
            )
            ->when(
                request('to'),
                fn($q) =>
                $q->whereDate('tanggal', '<=', request('to'))
            )
            ->when(
                request('sort') === 'oldest',
                fn($q) =>
                $q->orderBy('tanggal', 'asc'),
                fn($q) =>
                $q->orderBy('tanggal', 'desc')
            )
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => $journals->total(),
            'from'  => request('from'),
            'to'    => request('to'),
        ];

        return view('coachings.show', compact(
            'coaching',
            'journals',
            'summary'
        ));
    }

    public function update(Request $request, Coaching $coaching, Journal $journal)
    {
        $this->authorizeGuru($coaching);

        $validated = $request->validate([
            'tanggal'  => 'required|date',
            'catatan'  => 'required|string',
            'refleksi' => 'nullable|string',
        ]);

        $journal->update($validated);

        // 🔔 C.3.2 — ACTIVITY LOG (UPDATE)
        activity_log(
            'update',
            $journal,
            'Mengubah jurnal coaching'
        );

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Jurnal berhasil diperbarui');
    }

    public function destroy(Coaching $coaching, Journal $journal)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );

        // 🔔 C.3.2 — ACTIVITY LOG (DELETE)
        activity_log(
            'delete',
            $journal,
            'Menghapus jurnal coaching'
        );

        $journal->delete();

        return back()->with('success', 'Jurnal berhasil dihapus');
    }

    // Murid melihat jurnal miliknya
    public function myJournals()
    {
        $journals = \App\Models\Journal::whereHas('coaching', function ($q) {
            $q->where('murid_id', auth()->id());
        })
            ->latest()
            ->get();

        return view('journals.murid', compact('journals'));
    }

    private function authorizeGuru(Coaching $coaching)
    {
        if ($coaching->guru_id !== auth()->id()) {
            abort(403);
        }
    }

    public function journalsForParent()
    {
        $childrenIds = auth()->user()->children->pluck('id');

        $journals = Journal::whereIn('murid_id', $childrenIds)->get();

        return view('journals.parent', compact('journals'));
    }

    public function forParent()
    {
        $journals = Journal::whereHas('murid', function ($q) {
            $q->where('parent_id', auth()->id());
        })->latest()->get();

        return view('journals.parent', compact('journals'));
    }

    public function exportPdf(Request $request, Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $from = $request->query('from');
        $to   = $request->query('to');

        $journals = $coaching->journals()
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('tanggal', [$from, $to]);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.journals', [
            'coaching' => $coaching,
            'journals' => $journals,
            'from'     => $from,
            'to'       => $to,
        ])->setPaper('A4', 'portrait');

        $coachName = auth()->user()->name;
        $headmasterName = 'Nama Kepala Sekolah';

        return $pdf->download(
            'jurnal-coaching-' . now()->format('Ymd-His') . '.pdf'
        );
    }

    public function chart(Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $data = $coaching->journals()
            ->selectRaw('MONTH(tanggal) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('journals.chart', compact('coaching', 'data'));
    }
}
