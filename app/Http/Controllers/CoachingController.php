<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoachingRequest;
use App\Models\Coaching;
use App\Models\User;
use Illuminate\Http\Request;

class CoachingController extends Controller
{
    /**
     * =========================
     * DASHBOARD GURU
     * =========================
     */
    public function index()
    {
        $coachings = Coaching::where('guru_id', auth()->id())
            ->withCount('journals')   // 🔹 PENTING
            ->latest()
            ->get();

        $totalCoachings = Coaching::where('guru_id', auth()->id())->count();

        $totalJournals = \App\Models\Journal::whereHas('coaching', function ($q) {
            $q->where('guru_id', auth()->id());
        })->count();

        return view('dashboards.guru', compact(
            'coachings',
            'totalCoachings',
            'totalJournals'
        ));
    }

    /**
     * =========================
     * FORM TAMBAH COACHING
     * =========================
     */
    public function create()
    {
        $murids = User::where('role', 'murid')->get();

        return view('coachings.create', compact('murids'));
    }

    /**
     * =========================
     * SIMPAN COACHING
     * =========================
     */
    public function store(StoreCoachingRequest $request)
    {
        $coaching = Coaching::create([
            'guru_id'   => auth()->id(),
            'murid_id'  => $request->murid_id,
            'tujuan'    => $request->tujuan,
            'deskripsi' => $request->deskripsi,
            'status'    => 'draft',
        ]);

        // ✅ C.3.2 — Activity Log
        activity_log('create', $coaching, 'Membuat coaching');

        return redirect()
            ->route('coachings.index')
            ->with('success', 'Coaching berhasil dibuat');
    }

    /**
     * =========================
     * DETAIL COACHING (GURU)
     * =========================
     */
    public function show(Coaching $coaching)
    {
        $sort = request('sort', 'latest'); // default: latest

        $query = $coaching->journals();

        if ($sort === 'oldest') {
            $query->orderBy('tanggal', 'asc');
        } else {
            $query->orderBy('tanggal', 'desc'); // latest
        }

        $journals = $query->paginate(5)->withQueryString();

        return view('coachings.show', compact('coaching', 'journals', 'sort'));
    }

    /**
     * =========================
     * FORM EDIT
     * =========================
     */
    public function edit(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        return view('coachings.edit', compact('coaching'));
    }

    /**
     * =========================
     * UPDATE COACHING
     * =========================
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

    /**
     * =========================
     * HAPUS COACHING
     * =========================
     */
    public function destroy(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $coaching->delete();

        activity_log('delete', $coaching, 'Menghapus coaching');

        return back()->with('success', 'Coaching dihapus');
    }

    /**
     * =========================
     * AUTHORIZATION MANUAL
     * =========================
     */
    protected function authorizeGuru(Coaching $coaching)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
                $coaching->guru_id !== auth()->id(),
            403
        );
    }

    /**
     * =========================
     * MURID: LIST COACHING
     * =========================
     */
    public function forMurid()
    {
        $coachings = Coaching::where('murid_id', auth()->id())->get();

        return view('murid.coachings.index', compact('coachings'));
    }

    /**
     * =========================
     * MURID: DETAIL COACHING
     * =========================
     */
    public function showForMurid(Coaching $coaching)
    {
        abort_if($coaching->murid_id !== auth()->id(), 403);

        return view('coachings.show', compact('coaching'));
    }
}
