<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\Journal;
use App\Models\ActivityLog;
use App\Notifications\JournalCreated;
use App\Http\Requests\StoreJournalRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST JURNAL PER COACHING
    |--------------------------------------------------------------------------
    */
    public function index(Request $request, Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $journals = $this->filteredQuery($request, $coaching->journals())
            ->with('user:id,name')
            ->paginate(10)
            ->withQueryString();

        return view('journals.index', [
            'coaching' => $coaching,
            'journals' => $journals,
            ...$this->filterParams($request),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */
    public function create(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        return view('journals.create', compact('coaching'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreJournalRequest $request, Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        // WAJIB ADA
        $validated = $request->validated();

        $journal = $coaching->journals()->create([
            'user_id'  => auth()->id(),
            'tanggal'  => $validated['tanggal'],
            'catatan'  => $validated['catatan'],
            'refleksi' => $validated['refleksi'] ?? null,
        ]);

        $this->logActivity(
            'create',
            $journal,
            'Menambahkan jurnal coaching'
        );

        $parent = optional($coaching->murid)->parent;

        if ($parent) {
            $parent->notify(new JournalCreated($journal));
        }

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Jurnal berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Coaching $coaching, Journal $journal)
    {
        $this->authorizeGuru($coaching);
        $this->validateJournalOwnership($coaching, $journal);

        return view('journals.edit', compact('coaching', 'journal'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(StoreJournalRequest $request, Coaching $coaching, Journal $journal)
    {
        $this->authorizeGuru($coaching);
        $this->validateJournalOwnership($coaching, $journal);

        $journal->update($request->validated());

        $this->logActivity('update', $journal, 'Mengubah jurnal coaching');

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Jurnal berhasil diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Coaching $coaching, Journal $journal)
    {
        $this->authorizeGuru($coaching);
        $this->validateJournalOwnership($coaching, $journal);

        $this->logActivity('delete', $journal, 'Menghapus jurnal coaching');

        $journal->delete();

        return back()->with('success', 'Jurnal berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */
    public function exportPdf(Request $request, Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $journals = $this->filteredQuery($request, $coaching->journals())
            ->with('user:id,name')
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('pdf.journals', [
            'coaching' => $coaching,
            'journals' => $journals,
            ...$this->filterParams($request),
        ])->setPaper('A4', 'portrait');

        return $pdf->download(
            'jurnal-coaching-' . now()->format('Ymd-His') . '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHART BULANAN
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | MURID - JURNAL SAYA
    |--------------------------------------------------------------------------
    */
    public function myJournals(Request $request)
    {
        abort_unless(auth()->user()->role === 'murid', 403);

        $query = Journal::whereHas(
            'coaching',
            fn($q) =>
            $q->where('murid_id', auth()->id())
        );

        $journals = $this->filteredQuery($request, $query)
            ->with(['coaching.guru:id,name'])
            ->paginate(10)
            ->withQueryString();

        return view('journals.murid', [
            'journals' => $journals,
            ...$this->filterParams($request),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ORANG TUA - JURNAL ANAK
    |--------------------------------------------------------------------------
    */
    public function forParent(Request $request)
    {
        abort_unless(auth()->user()->role === 'orang_tua', 403);

        $query = Journal::whereHas(
            'coaching.murid',
            fn($q) =>
            $q->where('parent_id', auth()->id())
        );

        $journals = $this->filteredQuery($request, $query)
            ->with(['coaching.murid:id,name', 'coaching.guru:id,name'])
            ->paginate(10)
            ->withQueryString();

        return view('journals.parent', [
            'journals' => $journals,
            ...$this->filterParams($request),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER HELPER
    |--------------------------------------------------------------------------
    */
    private function filteredQuery(Request $request, $query)
    {
        return $query
            ->when(
                $request->from,
                fn($q) =>
                $q->whereDate('tanggal', '>=', $request->from)
            )
            ->when(
                $request->to,
                fn($q) =>
                $q->whereDate('tanggal', '<=', $request->to)
            )
            ->when(
                $request->sort === 'oldest',
                fn($q) => $q->orderBy('tanggal', 'asc'),
                fn($q) => $q->orderBy('tanggal', 'desc')
            );
    }

    private function filterParams(Request $request)
    {
        return [
            'from' => $request->from,
            'to'   => $request->to,
            'sort' => $request->sort ?? 'latest',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */
    private function authorizeGuru(Coaching $coaching)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );
    }

    private function validateJournalOwnership(Coaching $coaching, Journal $journal)
    {
        abort_if($journal->coaching_id !== $coaching->id, 404);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG
    |--------------------------------------------------------------------------
    */
    private function logActivity($action, $model, $description)
    {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model'       => class_basename($model),
            'model_id'    => $model->id,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
