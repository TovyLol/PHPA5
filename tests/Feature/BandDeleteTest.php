<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

//bevestiging bij verwijderen
class BandDeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Band $band;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->band = Band::factory()->create(['name' => 'Crosby, Stills & Nash']);
        $this->band->users()->attach($this->user);
    }

    public function test_beheerpagina_toont_gevarenzone(): void
    {
        $this->actingAs($this->user)
            ->get(route('bands.edit', $this->band))
            ->assertSee('Gevarenzone')
            ->assertSee('Definitief verwijderen');
    }

    public function test_band_verwijderen_met_juiste_naam(): void
    {
        Storage::fake('public');
        $pad = UploadedFile::fake()->image('foto.jpg')->store('bands', 'public');
        $this->band->update(['photo_path' => $pad]);
        $video = Video::factory()->for($this->band)->create();
        $collega = User::factory()->create();
        $this->band->users()->attach($collega);

        $this->actingAs($this->user)
            ->delete(route('bands.destroy', $this->band), ['confirm_name' => 'Crosby, Stills & Nash'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $this->assertModelMissing($this->band);
        $this->assertModelMissing($video); // video's gaan mee
        $this->assertDatabaseMissing('band_user', ['band_id' => $this->band->id]);
        Storage::disk('public')->assertMissing($pad); // foto van de server
        $this->assertModelExists($collega); // accounts blijven bestaan
    }

    public function test_verkeerde_naam_verwijdert_niets(): void
    {
        $this->actingAs($this->user)
            ->from(route('bands.edit', $this->band))
            ->delete(route('bands.destroy', $this->band), ['confirm_name' => 'crosby stills nash'])
            ->assertRedirect(route('bands.edit', $this->band))
            ->assertSessionHasErrorsIn('verwijderen', 'confirm_name');

        $this->assertModelExists($this->band);
    }

    public function test_zonder_bevestiging_wordt_niets_verwijderd(): void
    {
        $this->actingAs($this->user)
            ->delete(route('bands.destroy', $this->band))
            ->assertSessionHasErrorsIn('verwijderen', 'confirm_name');

        $this->assertModelExists($this->band);
    }

    public function test_vreemde_gebruiker_kan_band_niet_verwijderen(): void
    {
        $this->actingAs(User::factory()->create())
            ->delete(route('bands.destroy', $this->band), ['confirm_name' => 'Crosby, Stills & Nash'])
            ->assertForbidden();

        $this->assertModelExists($this->band);
    }

    public function test_gast_kan_band_niet_verwijderen(): void
    {
        $this->delete(route('bands.destroy', $this->band), ['confirm_name' => 'Crosby, Stills & Nash'])
            ->assertRedirect('/login');

        $this->assertModelExists($this->band);
    }
}
