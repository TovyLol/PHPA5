<?php

namespace App\Http\Requests;

use App\Support\YouTube;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Controleert een nieuwe YouTube-video (TO 4.4 en 7.4).
 */
class StoreVideoRequest extends FormRequest
{
    /**
     * Fouten van dit formulier komen in een eigen "error bag",
     * zodat ze niet door elkaar lopen met het formulier voor de bandgegevens.
     */
    protected $errorBag = 'video';

    public function authorize(): bool
    {
        // Alleen beheerders van deze band mogen video's toevoegen (BandPolicy)
        return $this->user()->can('update', $this->route('band'));
    }

    public function rules(): array
    {
        return [
            'youtube_url' => [
                'required', 'string', 'max:255',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (YouTube::id((string) $value) === null) {
                        $fail('Dit is geen geldige YouTube-link.');
                    }
                },
            ],
            'title' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'youtube_url.required' => 'Plak een YouTube-link.',
            'youtube_url.max' => 'De link is te lang.',
            'title.max' => 'De titel mag maximaal 100 tekens lang zijn.',
        ];
    }
}
