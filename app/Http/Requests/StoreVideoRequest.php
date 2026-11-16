<?php

namespace App\Http\Requests;

use App\Support\YouTube;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * valid video?
 */
class StoreVideoRequest extends FormRequest
{
    // datalaag nr3
    protected $errorBag = 'video';

    public function authorize(): bool
    {
        // Alleen beheerders 'update' permission
        return $this->user()->can('update', $this->route('band'));
    }

    public function rules(): array
    {
        return [
            'youtube_url' => [
                'required', 'string', 'max:255', // max 255
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
    }// 5 minuten die ik nooit weer terug krijg
}
