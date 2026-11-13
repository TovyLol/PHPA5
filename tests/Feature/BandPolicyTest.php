<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// test of policy wewrk
class BandPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_gekoppelde_gebruiker_mag_de_band_beheren(): void
    {
        $user = User::factory()->create();
        $band = Band::factory()->create();
        $band->users()->attach($user);

        $this->assertTrue($user->can('update', $band));
        $this->assertTrue($user->can('manageUsers', $band));
        $this->assertTrue($user->can('delete', $band));
    }

    public function test_niet_gekoppelde_gebruiker_mag_de_band_niet_beheren(): void
    {
        $vreemde = User::factory()->create();
        $band = Band::factory()->create();
        $band->users()->attach(User::factory()->create());

        $this->assertFalse($vreemde->can('update', $band));
        $this->assertFalse($vreemde->can('manageUsers', $band));
        $this->assertFalse($vreemde->can('delete', $band));
    }

    public function test_alle_gekoppelde_gebruikers_hebben_dezelfde_rechten(): void
    {
        [$eerste, $tweede] = User::factory(2)->create();
        $band = Band::factory()->create();
        $band->users()->attach([$eerste->id, $tweede->id]);

        $this->assertTrue($eerste->can('update', $band));
        $this->assertTrue($tweede->can('update', $band));
    }

    public function test_toegang_verdwijnt_na_ontkoppelen(): void
    {
        $user = User::factory()->create();
        $band = Band::factory()->create();
        $band->users()->attach($user);

        $band->users()->detach($user);

        $this->assertFalse($user->can('update', $band));
    }

    public function test_iedere_ingelogde_gebruiker_mag_een_band_aanmaken(): void
    {
        $this->assertTrue(User::factory()->create()->can('create', Band::class));
    }
}
