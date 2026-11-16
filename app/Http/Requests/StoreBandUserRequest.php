<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

// controleer of de gene die toevoegd ook wel echt beheerder is
class StoreBandUserRequest extends FormRequest
{
    // datalaag nr3
    protected $errorBag = 'members';

    public function authorize(): bool
    {
        return $this->user()->can('manageUsers', $this->route('band'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => trim((string) $this->input('email'))]);
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required', 'email', 'max:255',
                // check of de user wel bestaat
                'exists:users,email',
                // als het een keer niet lukt doe het dan geen 2de keer sukkel
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($this->route('band')->users()->where('email', $value)->exists()) {
                        $fail('Deze gebruiker is al beheerder van de band.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vul een e-mailadres in.',
            'email.email' => 'Dit is geen geldig e-mailadres.',
            'email.exists' => 'Er is geen account met dit e-mailadres. Vraag de persoon eerst te registreren.',
        ];
    } // 5 minuten die ik nooit weer terug krijg
}
