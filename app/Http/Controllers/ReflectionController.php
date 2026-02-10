<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\Reflection;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function create(Coaching $coaching)
    {
        $this->authorize('update', $coaching);

        if ($coaching->status !== 'completed') {
            abort(403, 'Coaching belum selesai.');
        }

        return view('reflections.create', compact('coaching'));
    }

    public function store(Request $request, Coaching $coaching)
    {
        $this->authorize('update', $coaching);

        $request->validate([
            'hasil_perkembangan' => 'required',
            'kendala'            => 'required',
            'evaluasi_metode'    => 'required',
            'catatan_guru'       => 'nullable',
        ]);

        Reflection::create([
            'coaching_id'       => $coaching->id,
            'hasil_perkembangan' => $request->hasil_perkembangan,
            'kendala'           => $request->kendala,
            'evaluasi_metode'   => $request->evaluasi_metode,
            'catatan_guru'      => $request->catatan_guru,
        ]);

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Refleksi berhasil disimpan.');
    }
}
