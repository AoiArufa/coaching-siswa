<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // Guru melihat jurnal coaching tertentu
    public function index(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $journals = $coaching->journals;

        return view('journals.index', compact('coaching', 'journals'));
    }

    public function create(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        return view('journals.create', compact('coaching'));
    }

    public function store(Request $request, Coaching $coaching)
    {
        // Authorization (sebaiknya via Policy, tapi kita rapikan dulu)
        $this->authorize('create', Journal::class);

        // Validasi
        $validated = $request->validate([
            'catatan' => 'required',
            'tanggal' => 'required|date',
            'refleksi' => 'nullable',
        ]);

        // Create jurnal (SATU KALI)
        $journal = $coaching->journals()->create($validated);

        // 🔔 C.3.2 — ACTIVITY LOG (DI SINI TEMPATNYA)
        activity_log(
            'create',
            $journal,
            'Menambahkan jurnal coaching'
        );

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Jurnal berhasil ditambahkan');
    }

    public function show(Journal $journal)
    {
        $this->authorize('view', $journal);

        return view('journals.show', compact('journal'));
    }

    // Murid melihat jurnal miliknya
    public function myJournals()
    {
        $journals = Journal::whereHas('coaching', function ($q) {
            $q->where('murid_id', auth()->id());
        })->get();

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
        $journals = Journal::whereHas('coaching', function ($q) {
            $q->where('murid_id', auth()->user()->anak_id);
        })->latest()->get();

        return view('ortu.journals.index', compact('journals'));
    }
}
