<?php

namespace Tests\Feature;

use App\Models\Band;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
// ik hou van de beatles

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_gast_ziet_alle_bands_zonder_zoekterm(): void
    {
        Band::factory()->create(['name' => 'The Beatles', 'slug' => 'the-beatles']);
        Band::factory()->create(['name' => 'Golden Earring']);

        $this->get(route('search'))
            ->assertOk()
            ->assertSee('The Beatles')
            ->assertSee('Golden Earring')
            ->assertSee(url('/epk/the-beatles')); // link gaat via de slug niet via het id
    }

    public function test_zoeken_op_deel_van_de_naam(): void
    {
        Band::factory()->create(['name' => 'The Beatles']);
        Band::factory()->create(['name' => 'Golden Earring']);

        $this->get(route('search', ['q' => 'beat']))
            ->assertOk()
            ->assertSee('The Beatles')
            ->assertDontSee('Golden Earring');
    }

    public function test_geen_resultaten_toont_melding(): void
    {
        Band::factory()->create(['name' => 'The Beatles']);

        $this->get(route('search', ['q' => 'xyz']))
            ->assertOk()
            ->assertSee('Geen bands gevonden voor')
            ->assertDontSee('The Beatles');
    }

    public function test_procentteken_is_geen_joker(): void
    {
        Band::factory()->create(['name' => 'The Beatles']);
        Band::factory()->create(['name' => '100% Rock']);

        $this->get(route('search', ['q' => '%']))
            ->assertOk()
            ->assertSee('100% Rock')
            ->assertDontSee('The Beatles');
    }

    public function test_zoekterm_wordt_ge_escaped(): void
    {
        $this->get(route('search', ['q' => '<script>alert(1)</script>']))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_resultaten_worden_gepagineerd(): void
    {
        Band::factory()->count(15)->create();

        $this->get(route('search'))->assertOk()->assertViewHas('bands', fn ($bands) => $bands->count() === 12);
        $this->get(route('search', ['page' => 2]))->assertOk()->assertViewHas('bands', fn ($bands) => $bands->count() === 3);
    }
}
