<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Coaching $coaching)
    {
        $this->authorize('view', $coaching);

        $materials = $coaching->materials()
            ->with('user:id,name')
            ->latest()
            ->get();

        return view('materials.index', compact(
            'coaching',
            'materials'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        return view('materials.create', compact('coaching'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, Coaching $coaching)
    {
        $this->authorizeGuru($coaching);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'file'          => 'nullable|file|max:5120',
            'external_link' => 'nullable|url',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')
                ->store('materials', 'public');
        }

        $coaching->materials()->create([
            'user_id'      => auth()->id(),
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'file_path'    => $filePath,
            'external_link' => $validated['external_link'] ?? null,
        ]);

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Bahan ajar berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Coaching $coaching, Material $material)
    {
        $this->authorizeGuru($coaching);
        $this->validateOwnership($coaching, $material);

        return view('materials.edit', compact(
            'coaching',
            'material'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Coaching $coaching, Material $material)
    {
        $this->authorizeGuru($coaching);
        $this->validateOwnership($coaching, $material);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'external_link' => 'nullable|url',
        ]);

        $material->update($validated);

        return redirect()
            ->route('coachings.show', $coaching)
            ->with('success', 'Bahan ajar diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(Coaching $coaching, Material $material)
    {
        $this->authorizeGuru($coaching);
        $this->validateOwnership($coaching, $material);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Bahan ajar dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
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

    private function validateOwnership(Coaching $coaching, Material $material)
    {
        abort_if(
            $material->coaching_id !== $coaching->id,
            404
        );
    }
}
