<?php

namespace Database\Seeders;

use App\Models\Band;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // test data aanmaken
    public function run(): void
    {
        // vaste test accounts
        $test = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tweede = User::factory()->create([
            'name' => 'Tweede Gebruiker',
            'email' => 'tweede@example.com',
        ]);

        $anderen = User::factory(4)->create();

        
        $gedeeld = Band::factory()->create(['name' => 'De Gedeelde Band', 'slug' => 'de-gedeelde-band']);
        $gedeeld->users()->attach([$test->id, $tweede->id]);

        
        $eigen = Band::factory()->create(['name' => 'Test Band', 'slug' => 'test-band']);
        $eigen->users()->attach($test);

        
        $overig = Band::factory(8)->create()->each(function (Band $band) use ($anderen) {
            $band->users()->attach($anderen->random(rand(1, 2)));
        });

        $overig->prepend($eigen)->prepend($gedeeld)->each(function (Band $band) {
            Video::factory(rand(0, 3))->for($band)->create();
        });
    }
}
