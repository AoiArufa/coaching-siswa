<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\Journal;

class DashboardParentController extends Controller
{
    public function index()
    {
        $childrenIds = auth()->user()->children->pluck('id');

        $coachings = Coaching::whereIn('murid_id', $childrenIds)->get();

        $journals = Journal::whereHas('coaching', function ($q) use ($childrenIds) {
            $q->whereIn('murid_id', $childrenIds);
        })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.ortu', [
            'childrenCount' => $childrenIds->count(),
            'coachingsCount' => $coachings->count(),
            'journalsCount' => $journals->count(),
            'latestJournals' => $journals,
        ]);
    }
}
