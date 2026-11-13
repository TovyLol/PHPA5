<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// check input
class UpdateBandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('band'));
    }

    public function rules(): array
    {  // max 100 # max 255 # max 10000 # max 2mb
        return [
            'name' => ['required', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string', 'max:10000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], +
            'remove_photo' => ['nullable', 'boolean'],
            'bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [ // foutmeldingen
            'name.required' => 'Vul een bandnaam in.',
            'name.max' => 'De bandnaam mag maximaal 100 tekens lang zijn.',
            'short_description.max' => 'De korte beschrijving mag maximaal 255 tekens lang zijn.',
            'biography.max' => 'De biografie mag maximaal 10.000 tekens lang zijn.',
            'photo.image' => 'Het bestand moet een afbeelding zijn.',
            'photo.mimes' => 'De foto moet een JPG-, PNG- of WebP-bestand zijn.',
            'photo.max' => 'De foto mag maximaal 2 MB groot zijn.',
            'photo.uploaded' => 'De foto kon niet worden geüpload. Is hij kleiner dan 2 MB?',
            'bg_color.required' => 'Kies een achtergrondkleur.',
            'bg_color.regex' => 'De achtergrondkleur is geen geldige kleur.',
            'text_color.required' => 'Kies een tekstkleur.',
            'text_color.regex' => 'De tekstkleur is geen geldige kleur.',
        ];
    }
}
