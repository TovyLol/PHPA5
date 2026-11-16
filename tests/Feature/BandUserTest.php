<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Beheerders van een band toevoegen en verwijderen (FO 3.3, TO 4.1 en 8.1).
 */
class BandUserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Band $band;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['name' => 'Ik Zelf']);
        $this->band = Band::factory()->create();
        $this->band->users()->attach($this->user);
    }

    public function test_beheerders_staan_op_de_beheerpagina(): void
    {
        $collega = User::factory()->create(['name' => 'Collega Gitarist']);
        $this->band->users()->attach($collega);

        $this->actingAs($this->user)
            ->get(route('bands.edit', $this->band))
            ->assertOk()
            ->assertSee('Collega Gitarist')
            ->assertSee('Band verlaten');
    }

    public function test_beheerder_toevoegen_via_email(): void
    {
        $nieuw = User::factory()->create(['email' => 'drummer@example.com']);

        $this->actingAs($this->user)
            ->post(route('bands.users.store', $this->band), ['email' => '  drummer@example.com '])
            ->assertRedirect(route('bands.edit', $this->band).'#beheerders')
            ->assertSessionHas('success');

        $this->assertTrue($this->band->users()->whereKey($nieuw->id)->exists());
        $this->assertTrue($nieuw->can('update', $this->band)); // nieuwe beheerder mag nu ook beheren
    }

    public function test_onbekend_email_wordt_geweigerd(): void
    {
        $this->actingAs($this->user)
            ->post(route('bands.users.store', $this->band), ['email' => 'bestaatniet@example.com'])
            ->assertSessionHasErrorsIn('members', 'email');

        $this->assertSame(1, $this->band->users()->count());
    }

    public function test_bestaande_beheerder_kan_niet_dubbel_worden_toegevoegd(): void
    {
        $this->actingAs($this->user)
            ->post(route('bands.users.store', $this->band), ['email' => $this->user->email])
            ->assertSessionHasErrorsIn('members', ['email' => 'Deze gebruiker is al beheerder van de band.']);

        $this->assertSame(1, $this->band->users()->count());
    }

    public function test_beheerder_verwijderen(): void
    {
        $collega = User::factory()->create();
        $this->band->users()->attach($collega);

        $this->actingAs($this->user)
            ->delete(route('bands.users.destroy', [$this->band, $collega]))
            ->assertRedirect(route('bands.edit', $this->band).'#beheerders')
            ->assertSessionHas('success');

        $this->assertFalse($collega->can('update', $this->band));
        $this->assertModelExists($collega); // het account zelf blijft bestaan
    }

    public function test_zelf_de_band_verlaten(): void
    {
        $this->band->users()->attach(User::factory()->create());

        $this->actingAs($this->user)
            ->delete(route('bands.users.destroy', [$this->band, $this->user]))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $this->assertFalse($this->user->can('update', $this->band));
    }

    public function test_laatste_beheerder_kan_niet_worden_verwijderd(): void
    {
        $this->actingAs($this->user)
            ->delete(route('bands.users.destroy', [$this->band, $this->user]))
            ->assertRedirect(route('bands.edit', $this->band).'#beheerders')
            ->assertSessionHas('error');

        $this->assertTrue($this->user->can('update', $this->band));
    }

    public function test_vreemde_gebruiker_kan_niets_aanpassen(): void
    {
        $vreemde = User::factory()->create();

        $this->actingAs($vreemde)
            ->post(route('bands.users.store', $this->band), ['email' => $vreemde->email])
            ->assertForbidden();

        $this->band->users()->attach(User::factory()->create());

        $this->actingAs($vreemde)
            ->delete(route('bands.users.destroy', [$this->band, $this->user]))
            ->assertForbidden();

        $this->assertSame(2, $this->band->users()->count());
    }

    public function test_gebruiker_die_geen_beheerder_is_kan_niet_worden_verwijderd(): void
    {
        $this->band->users()->attach(User::factory()->create());
        $buitenstaander = User::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('bands.users.destroy', [$this->band, $buitenstaander]))
            ->assertNotFound();
    }
}
