<?php

namespace Database\Factories;

use App\Models\Band;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

// test videos met random youtube links
class VideoFactory extends Factory
{
    public function definition(): array
    {
        $id = fake()->randomElement(['dQw4w9WgXcQ', 'jNQXAC9IVRw', '9bZkp7q19f0', 'kJQP7kiw5Fk']);

        return [
            'band_id' => Band::factory(),
            'youtube_url' => 'https://www.youtube.com/watch?v='.$id,
            'youtube_id' => $id,
            'title' => fake()->optional()->sentence(4),
        ];
    }
}
