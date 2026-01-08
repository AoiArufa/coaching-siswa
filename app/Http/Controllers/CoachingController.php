<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoachingRequest;
use App\Models\Coaching;
use App\Models\User;
use Illuminate\Http\Request;

class CoachingController extends Controller
{
    public function index()
    {
        $coachings = Coaching::where('guru_id', auth()->id())->latest()->get();

        return view('dashboards.guru', compact('coachings'));
    }

    public function create()
    {
        $murids = User::where('role', 'murid')->get();

        return view('coachings.create', compact('murids'));
    }

    public function store(StoreCoachingRequest $request)
    {
        $coaching = Coaching::create([
            'guru_id' => auth()->id(),
            'murid_id' => $request->murid_id,
            'title' => $request->title,
            'status' => $request->status,
        ]);

        // 🔔 C.3.2 — ACTIVITY LOG
        activity_log(
            'create',
            $coaching,
            'Membuat coaching baru'
        );

        return redirect()
            ->route('coachings.index')
            ->with('success', 'Coaching berhasil dibuat');
    }

    public function show(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $coaching->load(['murid', 'journals']);

        return view('coachings.show', compact('coaching'));
    }

    public function edit(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        return view('coachings.edit', compact('coaching'));
    }

    public function update(Request $request, Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $validated = $request->validate([
            'tujuan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,berjalan,selesai',
        ]);

        $coaching->update($validated);

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Coaching berhasil diperbarui');
    }

    public function destroy(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $coaching->delete();

        return back()->with('success', 'Coaching dihapus');
    }

    /**
     * 🔐 Authorization manual (tanpa Policy)
     */
    protected function authorizeGuru(Coaching $coaching)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
            $coaching->guru_id !== auth()->id(),
            403
        );
    }

    // =========================
    // ========== MURID =========
    // =========================

    public function forMurid()
    {
        $coachings = Coaching::where('murid_id', auth()->id())->get();

        return view('murid.coachings.index', compact('coachings'));
    }

    public function showForMurid(Coaching $coaching)
    {
        abort_if($coaching->murid_id !== auth()->id(), 403);

        return view('coachings.show', compact('coaching'));
    }
}
