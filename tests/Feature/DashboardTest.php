<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// dashboard test
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_gast_wordt_naar_inloggen_gestuurd(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/bands/create')->assertRedirect('/login');
    }

    public function test_home_stuurt_door_naar_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/home')
            ->assertRedirect('/dashboard');
    }
    //alleen eigen bands
    public function test_dashboard_toont_alleen_eigen_bands(): void
    {
        $user = User::factory()->create();
        $eigen = Band::factory()->create(['name' => 'Mijn Eigen Band']);
        $eigen->users()->attach($user); //koppel
        Band::factory()->create(['name' => 'Band Van Een Ander']);
        // double
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Mijn Eigen Band')
            ->assertDontSee('Band Van Een Ander');
    }

    public function test_dashboard_toont_lege_staat_zonder_bands(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertOk() // check status 200
            ->assertSee('Je beheert nog geen bands');
    }

    public function test_gebruiker_kan_een_band_aanmaken_en_wordt_beheerder(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/bands', ['name' => 'Nieuwe Band', 'short_description' => 'Rock uit Utrecht'])
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success');
            //db check
        $band = Band::where('name', 'Nieuwe Band')->firstOrFail();
        $this->assertSame('nieuwe-band', $band->slug);
        $this->assertTrue($band->users->contains($user));
    }

    public function test_bandnaam_is_verplicht(): void
    {
        $this->actingAs(User::factory()->create())
            ->from('/bands/create')
            ->post('/bands', ['name' => ''])
            ->assertRedirect('/bands/create')
            ->assertSessionHasErrors(['name' => 'Vul een bandnaam in.']);
        //bestaat die al?
        $this->assertDatabaseCount('bands', 0);
    }

    public function test_te_lange_beschrijving_wordt_geweigerd(): void
    {
        $this->actingAs(User::factory()->create()) 
            ->post('/bands', ['name' => 'Band', 'short_description' => str_repeat('a', 256)])
            ->assertSessionHasErrors('short_description');// max 255
    }
}
