<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function create(Coaching $coaching)
    {
        $this->authorize('update', $coaching);

        if ($coaching->status !== 'completed') {
            abort(403, 'Coaching belum selesai.');
        }

        return view('followups.create', compact('coaching'));
    }

    public function store(Request $request, Coaching $coaching)
    {
        $this->authorize('update', $coaching);

        $request->validate([
            'judul'          => 'required',
            'deskripsi'      => 'required',
            'target_tanggal' => 'nullable|date',
        ]);

        FollowUp::create([
            'coaching_id'  => $coaching->id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'target_tanggal' => $request->target_tanggal,
        ]);

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Rencana tindak lanjut berhasil ditambahkan.');
    }

    public function updateStatus(Coaching $coaching, FollowUp $followUp)
    {
        $this->authorize('update', $coaching);

        abort_if($followUp->coaching_id !== $coaching->id, 404);

        $followUp->update([
            'status' => request('status')
        ]);

        return back()->with('success', 'Status RTL diperbarui.');
    }
}
