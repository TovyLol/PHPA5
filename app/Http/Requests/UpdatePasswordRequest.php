<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    
    protected $errorBag = 'wachtwoord';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // confirmation
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Vul je huidige wachtwoord in.',
            'current_password.current_password' => 'Je huidige wachtwoord klopt niet.',
            'password.required' => 'Vul een nieuw wachtwoord in.',
            'password.min' => 'Je nieuwe wachtwoord moet minimaal 8 tekens zijn.',
            'password.confirmed' => 'De twee nieuwe wachtwoorden zijn niet hetzelfde.',
            'password.different' => 'Je nieuwe wachtwoord moet anders zijn dan je huidige.',
        ];
    }
}
