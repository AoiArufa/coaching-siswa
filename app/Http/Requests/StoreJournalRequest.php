<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tanggal' => [
                'required',
                'date',
                // Rule::unique('journals')
                //     ->where(function ($query) {
                //         return $query
                //             ->where('coaching_id', $this->route('coaching')->id)
                //             ->where('user_id', auth()->id());
                //     }),
            ],

            'catatan' => [
                'required',
                'string',
                'max:5000',
            ],

            'refleksi' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal' =>
            'Anda sudah mengisi jurnal pada tanggal tersebut.',
        ];
    }
}
