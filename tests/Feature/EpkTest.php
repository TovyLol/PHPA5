<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// openbare tests
class EpkTest extends TestCase
{
    use RefreshDatabase;

    private Band $band;

    protected function setUp(): void
    {
        parent::setUp();

        $this->band = Band::factory()->create([
            'name' => 'De Testers',
            'slug' => 'de-testers',
            'short_description' => 'Rock uit Rotterdam',
            'biography' => "Eerste regel\nTweede regel",
            'bg_color' => '#112233',
            'text_color' => '#fafafa',
        ]);
    }

    public function test_gast_kan_epk_bekijken_via_slug(): void
    {
        $this->get('/epk/de-testers')
            ->assertOk()
            ->assertSee('De Testers')
            ->assertSee('Rock uit Rotterdam');
    }

    public function test_epk_gebruikt_de_kleuren_van_de_band(): void
    {
        $this->get(route('epk.show', $this->band))
            ->assertSee('background-color: #112233', false)
            ->assertSee('color: #fafafa', false);
    }

    public function test_biografie_behoudt_regels_en_is_veilig(): void
    {
        $this->band->update(['biography' => "Regel een\n<b>geen html</b>"]);

        $this->get(route('epk.show', $this->band))
            ->assertSee('Regel een<br />', false)
            ->assertSee('&lt;b&gt;geen html&lt;/b&gt;', false)
            ->assertDontSee('<b>geen html</b>', false);
    }

    public function test_videos_worden_ingesloten(): void
    {
        $this->band->videos()->create([
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'title' => 'Live in Ahoy',
        ]);

        $this->get(route('epk.show', $this->band))
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false)
            ->assertSee('Live in Ahoy');
    }

    public function test_onbekende_slug_geeft_404(): void
    {
        $this->get('/epk/bestaat-niet')
            ->assertNotFound()
            ->assertSee('Pagina niet gevonden');
    }

    public function test_alleen_beheerder_ziet_bewerken_knop(): void
    {
        $beheerder = User::factory()->create();
        $this->band->users()->attach($beheerder);

        $this->get(route('epk.show', $this->band))->assertDontSee('Bewerken');
        $this->actingAs(User::factory()->create())->get(route('epk.show', $this->band))->assertDontSee('Bewerken');
        $this->actingAs($beheerder)->get(route('epk.show', $this->band))
            ->assertSee('Bewerken')
            ->assertSee(route('bands.edit', $this->band));
    }
}
