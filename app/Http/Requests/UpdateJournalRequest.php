<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tanggal'  => 'required|date',
            'catatan'  => 'required|string|max:5000',
            'refleksi' => 'nullable|string|max:5000',
        ];
    }
}
