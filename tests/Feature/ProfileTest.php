<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Profiel bekijken en aanpassen (FO 3.2, TO 7.2).
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Jan Jansen',
            'email' => 'jan@example.com',
            'password' => 'oud-wachtwoord',
        ]);
    }

    public function test_gast_wordt_naar_inloggen_gestuurd(): void
    {
        $this->get(route('profile.edit'))->assertRedirect('/login');
    }

    public function test_profiel_toont_eigen_gegevens(): void
    {
        $this->actingAs($this->user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Jan Jansen')
            ->assertSee('jan@example.com');
    }

    public function test_naam_en_email_aanpassen(): void
    {
        $this->actingAs($this->user)
            ->put(route('profile.update'), ['name' => 'Jan de Vries', 'email' => ' nieuw@example.com '])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->user->refresh();
        $this->assertSame('Jan de Vries', $this->user->name);
        $this->assertSame('nieuw@example.com', $this->user->email);
    }

    public function test_eigen_email_mag_blijven(): void
    {
        $this->actingAs($this->user)
            ->put(route('profile.update'), ['name' => 'Jan', 'email' => 'jan@example.com'])
            ->assertSessionHasNoErrors();
    }

    public function test_email_van_ander_account_is_niet_toegestaan(): void
    {
        User::factory()->create(['email' => 'bezet@example.com']);

        $this->actingAs($this->user)
            ->put(route('profile.update'), ['name' => 'Jan', 'email' => 'bezet@example.com'])
            ->assertSessionHasErrors(['email' => 'Dit e-mailadres wordt al door een ander account gebruikt.']);

        $this->assertSame('jan@example.com', $this->user->fresh()->email);
    }

    public function test_wachtwoord_wijzigen(): void
    {
        $this->actingAs($this->user)
            ->put(route('profile.password'), [
                'current_password' => 'oud-wachtwoord',
                'password' => 'nieuw-wachtwoord',
                'password_confirmation' => 'nieuw-wachtwoord',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('nieuw-wachtwoord', $this->user->fresh()->password));
    }

    public function test_verkeerd_huidig_wachtwoord_wordt_geweigerd(): void
    {
        $this->actingAs($this->user)
            ->put(route('profile.password'), [
                'current_password' => 'fout',
                'password' => 'nieuw-wachtwoord',
                'password_confirmation' => 'nieuw-wachtwoord',
            ])
            ->assertSessionHasErrorsIn('wachtwoord', ['current_password' => 'Je huidige wachtwoord klopt niet.']);

        $this->assertTrue(Hash::check('oud-wachtwoord', $this->user->fresh()->password));
    }

    public function test_nieuw_wachtwoord_moet_bevestigd_en_lang_genoeg_zijn(): void
    {
        $this->actingAs($this->user)
            ->put(route('profile.password'), [
                'current_password' => 'oud-wachtwoord',
                'password' => 'kort',
                'password_confirmation' => 'anders',
            ])
            ->assertSessionHasErrorsIn('wachtwoord', 'password');

        $this->assertTrue(Hash::check('oud-wachtwoord', $this->user->fresh()->password));
    }
}
