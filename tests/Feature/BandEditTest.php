<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// band editen
class BandEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Band $band;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        //koppeling
        $this->user = User::factory()->create();
        $this->band = Band::factory()->create(['name' => 'Oude Naam', 'slug' => 'oude-naam']);
        $this->band->users()->attach($this->user);
    }

    private function data(array $overrides = []): array
    {//default data
        return array_merge([
            'name' => 'Nieuwe Naam',
            'short_description' => 'Korte tekst',
            'biography' => "Eerste alinea.\n\nTweede alinea.",
            'bg_color' => '#112233',
            'text_color' => '#ffffff',
        ], $overrides);
    }

    public function test_beheerder_ziet_de_beheerpagina(): void
    {//beheerder kan de pagina zien
        $this->actingAs($this->user)
            ->get(route('bands.edit', $this->band))
            ->assertOk()
            ->assertSee('Oude Naam beheren');
    }

    public function test_dashboard_heeft_beheren_knop(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertSee(route('bands.edit', $this->band));
    }

    public function test_niet_gekoppelde_gebruiker_krijgt_403(): void
    {
        $vreemde = User::factory()->create();

        $this->actingAs($vreemde)
            ->get(route('bands.edit', $this->band))
            ->assertForbidden()
            ->assertSee('Geen toegang');

        $this->actingAs($vreemde)
            ->put(route('bands.update', $this->band), $this->data())
            ->assertForbidden();

        $this->assertSame('Oude Naam', $this->band->fresh()->name);
    }

    public function test_beheerder_kan_gegevens_en_kleuren_aanpassen(): void
    {
        $this->actingAs($this->user)
            ->put(route('bands.update', $this->band), $this->data())
            ->assertRedirect(route('bands.edit', $this->band))
            ->assertSessionHas('success');

        $band = $this->band->fresh();
        $this->assertSame('Nieuwe Naam', $band->name);
        $this->assertSame("Eerste alinea.\n\nTweede alinea.", $band->biography);
        $this->assertSame('#112233', $band->bg_color);
        $this->assertSame('#ffffff', $band->text_color);
    }

    public function test_slug_blijft_gelijk_bij_nieuwe_naam(): void
    {
        $this->actingAs($this->user)->put(route('bands.update', $this->band), $this->data());

        $this->assertSame('oude-naam', $this->band->fresh()->slug);
    }

    public function test_foto_uploaden_en_vervangen(): void
    {
        $this->actingAs($this->user)->put(route('bands.update', $this->band), $this->data([
            'photo' => UploadedFile::fake()->image('eerste.jpg', 800, 600),
        ]));
        $eerste = $this->band->fresh()->photo_path;
        Storage::disk('public')->assertExists($eerste);

        $this->actingAs($this->user)->put(route('bands.update', $this->band), $this->data([
            'photo' => UploadedFile::fake()->image('tweede.png', 800, 600),
        ]));
        $tweede = $this->band->fresh()->photo_path;

        Storage::disk('public')->assertExists($tweede);
        Storage::disk('public')->assertMissing($eerste); // oude foto is opgeruimd
    }

    public function test_foto_verwijderen(): void
    {
        $this->actingAs($this->user)->put(route('bands.update', $this->band), $this->data([
            'photo' => UploadedFile::fake()->image('foto.jpg'),
        ]));
        $pad = $this->band->fresh()->photo_path;

        $this->actingAs($this->user)->put(route('bands.update', $this->band), $this->data([
            'remove_photo' => '1',
        ]));

        $this->assertNull($this->band->fresh()->photo_path);
        Storage::disk('public')->assertMissing($pad);
    }

    public function test_verkeerd_bestandstype_wordt_geweigerd(): void
    {
        $this->actingAs($this->user)
            ->put(route('bands.update', $this->band), $this->data([
                'photo' => UploadedFile::fake()->create('virus.pdf', 100, 'application/pdf'),
            ]))
            ->assertSessionHasErrors('photo');

        $this->assertNull($this->band->fresh()->photo_path);
    }

    public function test_te_grote_foto_wordt_geweigerd(): void
    {
        $this->actingAs($this->user)
            ->put(route('bands.update', $this->band), $this->data([
                'photo' => UploadedFile::fake()->image('groot.jpg')->size(3000), // 3 MB
            ]))
            ->assertSessionHasErrors(['photo' => 'De foto mag maximaal 2 MB groot zijn.']);
    }

    public function test_ongeldige_kleur_wordt_geweigerd(): void
    {
        $this->actingAs($this->user)
            ->put(route('bands.update', $this->band), $this->data(['bg_color' => 'rood']))
            ->assertSessionHasErrors('bg_color');

        $this->assertNotSame('rood', $this->band->fresh()->bg_color);
    }
}
