<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoachingRequest;
use App\Models\Coaching;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoachingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GURU
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->authorizeGuruGlobal();

        $coachings = Coaching::with([
            'murid:id,name'
        ])
            ->withCount('journals')
            ->where('guru_id', auth()->id())
            ->latest()
            ->get();

        $totalCoachings = $coachings->count();

        $totalJournals = Journal::whereHas('coaching', function ($q) {
            $q->where('guru_id', auth()->id());
        })->count();

        return view('dashboards.guru', compact(
            'coachings',
            'totalCoachings',
            'totalJournals'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ANALYTICS DASHBOARD GURU
    |--------------------------------------------------------------------------
    */
    public function analytics()
    {
        $this->authorizeGuruGlobal();

        $totalCoachings = Coaching::where('guru_id', auth()->id())->count();

        $totalJournals = Journal::whereHas('coaching', function ($q) {
            $q->where('guru_id', auth()->id());
        })->count();

        $avgPerCoaching = $totalCoachings > 0
            ? round($totalJournals / $totalCoachings, 1)
            : 0;

        $chartData = Journal::whereHas('coaching', function ($q) {
            $q->where('guru_id', auth()->id());
        })
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('guru.analytics', compact(
            'totalCoachings',
            'totalJournals',
            'avgPerCoaching',
            'chartData'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->authorizeGuruGlobal();

        $murids = User::where('role', 'murid')
            ->orderBy('name')
            ->get();

        return view('coachings.create', compact('murids'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreCoachingRequest $request)
    {
        $this->authorizeGuruGlobal();

        $coaching = Coaching::create([
            'guru_id'   => auth()->id(),
            'murid_id'  => $request->murid_id,
            'tujuan'    => $request->tujuan,
            'deskripsi' => $request->deskripsi,
            'status'    => 'draft',
        ]);

        activity_log('create', $coaching, 'Membuat coaching');

        return redirect()
            ->route('coachings.index')
            ->with('success', 'Coaching berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL COACHING (GURU)
    |--------------------------------------------------------------------------
    */
    public function show(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $coaching->load([
            'murid:id,name',
            'journals.user:id,name'
        ]);

        $sort = request('sort', 'latest');

        $journals = $coaching->journals()
            ->when(
                $sort === 'oldest',
                fn($q) => $q->orderBy('tanggal', 'asc'),
                fn($q) => $q->orderBy('tanggal', 'desc')
            )
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $summaryData = $coaching->journals()
            ->select('tanggal')
            ->orderBy('tanggal')
            ->get();

        $total = $summaryData->count();

        $firstDate = optional($summaryData->first())->tanggal;
        $lastDate  = optional($summaryData->last())->tanggal;

        $months = $summaryData->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m');
        })->count();

        $avgPerMonth = $months > 0
            ? round($total / $months, 1)
            : 0;

        $summary = [
            'total'         => $total,
            'first_date'    => $firstDate,
            'last_date'     => $lastDate,
            'months'        => $months,
            'avg_per_month' => $avgPerMonth,
        ];

        $progress = min($total * 10, 100);

        return view('coachings.show', compact(
            'coaching',
            'journals',
            'sort',
            'summary',
            'progress'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        return view('coachings.edit', compact('coaching'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $validated = $request->validate([
            'tujuan'    => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|in:draft,berjalan,selesai',
        ]);

        $coaching->update($validated);

        activity_log('update', $coaching, 'Mengubah coaching');

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Coaching berhasil diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        activity_log('delete', $coaching, 'Menghapus coaching');

        $coaching->delete();

        return redirect()
            ->route('coachings.index')
            ->with('success', 'Coaching berhasil dihapus');
    }

    public function completeForm(Coaching $coaching)
    {
        if ($coaching->progressPercentage() < 100) {
            return redirect()
                ->route('coachings.show', $coaching)
                ->with('error', 'Semua tahap belum selesai.');
        }

        return view('coachings.complete', compact('coaching'));
    }

    public function complete(Request $request, Coaching $coaching)
    {
        if ($coaching->progressPercentage() < 100) {
            return back()->with('error', 'Progress belum 100%.');
        }

        $request->validate([
            'final_evaluation' => 'required'
        ]);

        $coaching->update([
            'final_evaluation' => $request->final_evaluation,
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Coaching berhasil diselesaikan.');
    }

    public function report(Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $coaching->load([
            'sessions.stage',
            'journals'
        ]);

        return view('coachings.report', compact('coaching'));
    }

    /*
    |--------------------------------------------------------------------------
    | MURID SECTION
    |--------------------------------------------------------------------------
    */
    public function forMurid()
    {
        abort_if(auth()->user()->role !== 'murid', 403);

        $coachings = Coaching::where('murid_id', auth()->id())
            ->withCount('journals')
            ->latest()
            ->get();

        return view('murid.coachings.index', compact('coachings'));
    }

    public function showForMurid(Coaching $coaching)
    {
        abort_if(
            auth()->user()->role !== 'murid' ||
                $coaching->murid_id !== auth()->id(),
            403
        );

        $journals = $coaching->journals()
            ->with('user:id,name')
            ->orderByDesc('tanggal')
            ->paginate(5);

        return view('murid.coachings.show', compact(
            'coaching',
            'journals'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION HELPERS
    |--------------------------------------------------------------------------
    */
    protected function authorizeGuru(Coaching $coaching)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );
    }

    protected function authorizeGuruGlobal()
    {
        abort_if(auth()->user()->role !== 'guru', 403);
    }
}
