<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Controleert het toevoegen van een beheerder aan een band (FO 3.3, TO 4.1).
 */
class StoreBandUserRequest extends FormRequest
{
    /**
     * Eigen "error bag", zodat fouten alleen bij het beheerdersformulier verschijnen.
     */
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
                // De persoon moet al een account hebben (er worden geen uitnodigingen gemaild)
                'exists:users,email',
                // Niet twee keer dezelfde beheerder toevoegen
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
    }
}
