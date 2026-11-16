<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// alle functies van inloggen
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_inlogpagina_is_nederlands(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Inloggen')
            ->assertSee('Ingelogd blijven')
            ->assertDontSee('Remember Me');
    }

    public function test_inloggen_met_juiste_gegevens(): void
    {
        $user = User::factory()->create(['email' => 'jan@example.com', 'password' => 'geheim123']);

        $this->post('/login', ['email' => 'jan@example.com', 'password' => 'geheim123'])
            ->assertRedirect('/home'); // /home stuurt door naar /dashboard

        $this->assertAuthenticatedAs($user);
    }

    public function test_verkeerd_wachtwoord_geeft_nederlandse_melding(): void
    {
        User::factory()->create(['email' => 'jan@example.com', 'password' => 'geheim123']);

        $this->from('/login')
            ->post('/login', ['email' => 'jan@example.com', 'password' => 'fout'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Deze combinatie van e-mailadres en wachtwoord is onjuist.']);

        $this->assertGuest();
    }

    public function test_lege_velden_geven_nederlandse_meldingen(): void
    {
        $this->post('/login', [])
            ->assertSessionHasErrors([
                'email' => 'Vul je e-mailadres in.',
                'password' => 'Vul je wachtwoord in.',
            ]);
    }

    public function test_na_vijf_foute_pogingen_even_wachten(): void
    {
        User::factory()->create(['email' => 'jan@example.com', 'password' => 'geheim123']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'jan@example.com', 'password' => 'fout']);
        }

        // ook met het juiste wachtwoord lukt het nu even niet
        $this->post('/login', ['email' => 'jan@example.com', 'password' => 'geheim123'])
            ->assertSessionHasErrors('email');

        $this->assertStringContainsString('Te veel inlogpogingen', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_uitloggen(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_registreren_maakt_account_en_logt_in(): void
    {
        $this->post('/register', [
            'name' => 'Nieuwe Gitarist',
            'email' => 'gitarist@example.com',
            'password' => 'wachtwoord123',
            'password_confirmation' => 'wachtwoord123',
        ])->assertRedirect('/home');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'gitarist@example.com', 'name' => 'Nieuwe Gitarist']);
    }

    public function test_registreren_met_bestaand_email_wordt_geweigerd(): void
    {
        User::factory()->create(['email' => 'bezet@example.com']);

        $this->post('/register', [
            'name' => 'Iemand',
            'email' => 'bezet@example.com',
            'password' => 'wachtwoord123',
            'password_confirmation' => 'wachtwoord123',
        ])->assertSessionHasErrors(['email' => 'Dit e-mailadres is al in gebruik.']);

        $this->assertGuest();
        $this->assertSame(1, User::count());
    }

    public function test_registreren_met_te_kort_wachtwoord(): void
    {
        $this->post('/register', [
            'name' => 'Iemand',
            'email' => 'iemand@example.com',
            'password' => 'kort',
            'password_confirmation' => 'kort',
        ])->assertSessionHasErrors(['password' => 'Je wachtwoord moet minimaal 8 tekens zijn.']);

        $this->assertGuest();
    }
}
