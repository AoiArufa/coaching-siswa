<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'guru';
    }

    public function rules()
    {
        return [
            'murid_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,berjalan,selesai'],
        ];
    }
}
