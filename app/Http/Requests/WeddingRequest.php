<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeddingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bride_name'         => 'required|string|max:255',
            'groom_name'         => 'required|string|max:255',
            'wedding_date'       => 'nullable|date|after:today',
            'wedding_type'       => 'required|in:sinhala,tamil,muslim,christian',
            'venue'              => 'nullable|string|max:255',
            'description'        => 'nullable|string|max:1000',
            'allow_admin_access' => 'sometimes|boolean',
        ];
    }
}
