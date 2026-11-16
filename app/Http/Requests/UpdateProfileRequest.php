<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    // alleen ingelogd
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => trim((string) $this->input('email'))]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // uniek, maar je eigen huidige adres mag natuurlijk blijven
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vul je naam in.',
            'name.max' => 'Je naam mag maximaal 255 tekens zijn.',
            'email.required' => 'Vul je e-mailadres in.',
            'email.email' => 'Dit is geen geldig e-mailadres.',
            'email.unique' => 'Dit e-mailadres wordt al door een ander account gebruikt.',
        ];
    }
}
