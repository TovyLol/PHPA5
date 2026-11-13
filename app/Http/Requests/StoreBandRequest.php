<?php

namespace App\Http\Requests;

use App\Models\Band;
use Illuminate\Foundation\Http\FormRequest;

// info validation
class StoreBandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Band::class);
    }
    // max 100 max 255
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:255'],
        ];
    }

    // foutmeldingen misschien komt er nog een vertaling bij
    public function messages(): array
    {
        return [
            'name.required' => 'Vul een bandnaam in.',
            'name.max' => 'De bandnaam mag maximaal 100 tekens lang zijn.',
            'short_description.max' => 'De korte beschrijving mag maximaal 255 tekens lang zijn.',
        ];
    }
}
