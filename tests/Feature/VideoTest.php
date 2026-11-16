<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Video's toevoegen en verwijderen (FO 3.3, TO 4.4 en 8.1).
 */
class VideoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Band $band;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->band = Band::factory()->create();
        $this->band->users()->attach($this->user);
    }

    public function test_beheerder_kan_video_toevoegen(): void
    {
        $this->actingAs($this->user)
            ->post(route('videos.store', $this->band), [
                'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ?si=abc',
                'title' => 'Live in Paradiso',
            ])
            ->assertRedirect(route('bands.edit', $this->band).'#videos')
            ->assertSessionHas('success');

        $video = $this->band->videos()->sole();
        $this->assertSame('dQw4w9WgXcQ', $video->youtube_id);
        $this->assertSame('Live in Paradiso', $video->title);
    }

    public function test_video_staat_op_de_beheerpagina(): void
    {
        Video::factory()->for($this->band)->create(['title' => 'Mijn Clip']);

        $this->actingAs($this->user)
            ->get(route('bands.edit', $this->band))
            ->assertOk()
            ->assertSee('Mijn Clip');
    }

    public function test_aantal_videos_is_niet_beperkt(): void
    {
        foreach (range(1, 12) as $i) {
            $this->actingAs($this->user)->post(route('videos.store', $this->band), [
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ]);
        }

        $this->assertSame(12, $this->band->videos()->count());
    }

    public function test_ongeldige_link_wordt_geweigerd(): void
    {
        $this->actingAs($this->user)
            ->post(route('videos.store', $this->band), ['youtube_url' => 'https://vimeo.com/123'])
            ->assertSessionHasErrorsIn('video', ['youtube_url' => 'Dit is geen geldige YouTube-link.']);

        $this->assertDatabaseCount('videos', 0);
    }

    public function test_link_is_verplicht(): void
    {
        $this->actingAs($this->user)
            ->post(route('videos.store', $this->band), ['youtube_url' => ''])
            ->assertSessionHasErrorsIn('video', ['youtube_url' => 'Plak een YouTube-link.']);
    }

    public function test_vreemde_gebruiker_kan_geen_video_toevoegen(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('videos.store', $this->band), ['youtube_url' => 'https://youtu.be/dQw4w9WgXcQ'])
            ->assertForbidden();

        $this->assertDatabaseCount('videos', 0);
    }

    public function test_beheerder_kan_video_verwijderen(): void
    {
        $video = Video::factory()->for($this->band)->create();

        $this->actingAs($this->user)
            ->delete(route('videos.destroy', [$this->band, $video]))
            ->assertRedirect(route('bands.edit', $this->band).'#videos')
            ->assertSessionHas('success');

        $this->assertModelMissing($video);
    }

    public function test_vreemde_gebruiker_kan_geen_video_verwijderen(): void
    {
        $video = Video::factory()->for($this->band)->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('videos.destroy', [$this->band, $video]))
            ->assertForbidden();

        $this->assertModelExists($video);
    }

    public function test_video_van_andere_band_kan_niet_via_eigen_band_worden_verwijderd(): void
    {
        // De gebruiker beheert $this->band, maar probeert een video van een andere band
        // te verwijderen door die in de URL van zijn eigen band te zetten.
        $andereVideo = Video::factory()->for(Band::factory())->create();

        $this->actingAs($this->user)
            ->delete(route('videos.destroy', [$this->band, $andereVideo]))
            ->assertNotFound();

        $this->assertModelExists($andereVideo);
    }
}
