<?php

namespace Database\Factories;

use App\Models\Band;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
// maak test bands aan
class BandFactory extends Factory
{
    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(fake()->numberBetween(1, 3), true));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(10),
            'biography' => implode("\n\n", fake()->paragraphs(3)),
            'photo_path' => null,
            'bg_color' => fake()->randomElement(['#ffffff', '#111111', '#1e293b', '#fef3c7', '#0f766e']),
            'text_color' => fake()->randomElement(['#000000', '#f8fafc']),
        ];
    }
}
