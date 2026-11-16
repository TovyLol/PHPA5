<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
// fo 3.3
class DestroyBandRequest extends FormRequest
{
    protected $errorBag = 'verwijderen';

    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->route('band'));
    }

    public function rules(): array
    {
        return [
            // precies overschrijven van de naam
            'confirm_name' => ['required', 'string', Rule::in([$this->route('band')->name])],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm_name.required' => 'Typ de naam van de band om te bevestigen.',
            'confirm_name.in' => 'De naam komt niet overeen. De band is niet verwijderd.',
        ];
    }
}
