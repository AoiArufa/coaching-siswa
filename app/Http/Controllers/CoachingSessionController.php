<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\CoachingStage;
use Illuminate\Http\Request;

class CoachingSessionController extends Controller
{
    public function create(Coaching $coaching)
    {
        if ($coaching->status === 'completed') {
            return redirect()
                ->route('coachings.show', $coaching)
                ->with('error', 'Coaching sudah selesai.');
        }

        $allStages = \App\Models\CoachingStage::orderBy('order')->get();

        $completedStageIds = $coaching->sessions()
            ->pluck('coaching_stage_id')
            ->unique()
            ->toArray();

        $nextStage = $allStages->first(function ($stage) use ($completedStageIds) {
            return !in_array($stage->id, $completedStageIds);
        });

        return view('sessions.create', compact(
            'coaching',
            'allStages',
            'completedStageIds',
            'nextStage'
        ));
    }

    public function store(Request $request, Coaching $coaching)
    {
        $request->validate([
            'coaching_stage_id' => 'required|exists:coaching_stages,id',
            'session_date' => 'required|date',
            'notes' => 'nullable'
        ]);

        // VALIDASI URUTAN TAHAP
        // $lastSession = $coaching->sessions()
        //     ->with('stage')
        //     ->get()
        //     ->sortByDesc(fn($s) => $s->stage->order)
        //     ->first();

        // if ($lastSession) {
        //     $selectedStage = CoachingStage::find($request->coaching_stage_id);

        //     if ($selectedStage->order < $lastSession->stage->order) {
        //         return back()->with('error', 'Tidak boleh kembali ke tahap sebelumnya.');
        //     }
        // }
        $allStages = \App\Models\CoachingStage::orderBy('order')->get();
        $completedStageIds = $coaching->sessions()
            ->pluck('coaching_stage_id')
            ->unique()
            ->toArray();

        $nextStage = $allStages->first(function ($stage) use ($completedStageIds) {
            return !in_array($stage->id, $completedStageIds);
        });

        if ($nextStage && $request->coaching_stage_id != $nextStage->id) {
            return back()->with('error', 'Tahap belum tersedia.');
        }

        $coaching->sessions()->create($request->all());

        // Auto complete jika 100%
        if ($coaching->progressPercentage() == 100) {
            $coaching->update(['status' => 'completed']);
        }

        return redirect()->route('coachings.show', $coaching->id)
            ->with('success', 'Sesi berhasil dibuat');
    }
}
