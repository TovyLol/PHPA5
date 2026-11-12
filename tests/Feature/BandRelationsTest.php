<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

//controlleeeeer
class BandRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_een_band_kan_meerdere_gebruikers_hebben(): void
    {
        $band = Band::factory()->create();
        $users = User::factory(2)->create();

        $band->users()->attach($users);

        $this->assertCount(2, $band->users);
    }

    public function test_een_gebruiker_kan_meerdere_bands_beheren(): void
    {
        $user = User::factory()->create();
        $user->bands()->attach(Band::factory(3)->create());

        $this->assertCount(3, $user->bands);
    }

    public function test_een_band_heeft_meerdere_videos_en_een_video_hoort_bij_een_band(): void
    {
        $band = Band::factory()->create();
        $video = Video::factory()->for($band)->create();
        Video::factory()->for($band)->create();

        $this->assertCount(2, $band->videos);
        $this->assertTrue($video->band->is($band));
    }

    public function test_slug_wordt_automatisch_en_uniek_aangemaakt(): void
    {
        $eerste = Band::create(['name' => 'The Beatles']);
        $tweede = Band::create(['name' => 'The Beatles']);

        $this->assertSame('the-beatles', $eerste->slug);
        $this->assertSame('the-beatles-2', $tweede->slug);
    }

    public function test_verwijderen_van_band_verwijdert_videos_en_koppelingen(): void
    {
        $band = Band::factory()->create();
        $user = User::factory()->create();
        $band->users()->attach($user);
        Video::factory()->for($band)->create();

        $band->delete();

        $this->assertDatabaseCount('videos', 0);
        $this->assertDatabaseCount('band_user', 0);
        $this->assertDatabaseHas('users', ['id' => $user->id]); // gebruiker blijft bestaan
    }
}
