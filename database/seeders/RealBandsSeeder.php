<?php

namespace Database\Seeders;

use App\Models\Band;
use App\Models\User;
use Illuminate\Database\Seeder;

// echte band als data (ja dit is ai)
class RealBandsSeeder extends Seeder
{
    public function run(): void
    {
        $test = User::where('email', 'test@example.com')->first();

        if (! $test) {
            $this->command?->warn('Geen test@example.com gevonden, draai eerst: php artisan migrate:fresh --seed');

            return;
        }

        foreach ($this->bands() as $data) {
            $videos = $data['videos'];
            unset($data['videos']);

            // updateOrCreate op slug: nog een keer draaien maakt geen dubbele bands
            $band = Band::updateOrCreate(['slug' => $data['slug']], $data);
            $band->users()->syncWithoutDetaching([$test->id]);

            $band->videos()->delete();
            foreach ($videos as $id => $title) {
                $band->videos()->create([
                    'youtube_url' => 'https://www.youtube.com/watch?v='.$id,
                    'youtube_id' => $id,
                    'title' => $title,
                ]);
            }
        }
    }

    private function bands(): array
    {
        return [
            [
                'name' => 'Queen',
                'slug' => 'queen',
                'short_description' => 'Britse rockband rond Freddie Mercury, Brian May, Roger Taylor en John Deacon.',
                'biography' => "Queen werd in 1970 opgericht in Londen.\n\nDe band combineerde hardrock met opera, glamrock en pop, en werd wereldberoemd door nummers als Bohemian Rhapsody, We Will Rock You en Don't Stop Me Now.\n\nHun optreden tijdens Live Aid in 1985 geldt als een van de beste liveoptredens aller tijden.",
                'bg_color' => '#1a1a2e',
                'text_color' => '#f5d76e',
                'videos' => [
                    'fJ9rUzIMcZQ' => 'Bohemian Rhapsody',
                ],
            ],
            [
                'name' => 'Nirvana',
                'slug' => 'nirvana',
                'short_description' => 'Grungeband uit Aberdeen, Washington.',
                'biography' => "Nirvana werd in 1987 opgericht door Kurt Cobain en Krist Novoselic. Dave Grohl kwam in 1990 bij de band als drummer.\n\nMet het album Nevermind (1991) en de single Smells Like Teen Spirit bracht de band grunge naar het grote publiek.",
                'bg_color' => '#f4f1ea',
                'text_color' => '#1b1b1b',
                'videos' => [
                    'hTWKbfoikeg' => 'Smells Like Teen Spirit',
                ],
            ],
            [
                'name' => 'a-ha',
                'slug' => 'a-ha',
                'short_description' => 'Noorse synthpopband uit Oslo.',
                'biography' => "a-ha bestaat uit Morten Harket, Magne Furuholmen en Paul Waaktaar-Savoy.\n\nDe band brak in 1985 wereldwijd door met Take On Me, bekend van de baanbrekende videoclip waarin potloodtekeningen en echte beelden door elkaar lopen.",
                'bg_color' => '#e8f1f8',
                'text_color' => '#13294b',
                'videos' => [
                    'djV11Xbc914' => 'Take On Me',
                ],
            ],
            [
                'name' => 'Toto',
                'slug' => 'toto',
                'short_description' => 'Amerikaanse rockband uit Los Angeles.',
                'biography' => "Toto werd in 1977 opgericht door een groep ervaren studiomuzikanten.\n\nHet album Toto IV (1982) won meerdere Grammy's en bevat de hits Rosanna en Africa.",
                'bg_color' => '#c9733a',
                'text_color' => '#1a0f05',
                'videos' => [
                    'FTQbiNvZqaY' => 'Africa',
                ],
            ],
            [
                'name' => "Guns N' Roses",
                'slug' => 'guns-n-roses',
                'short_description' => 'Hardrockband uit Los Angeles.',
                'biography' => "Guns N' Roses werd in 1985 opgericht met onder anderen Axl Rose en Slash.\n\nHun debuutalbum Appetite for Destruction (1987) is een van de bestverkochte debuutalbums ooit, met Sweet Child O' Mine als grootste hit.",
                'bg_color' => '#0d0d0d',
                'text_color' => '#e63946',
                'videos' => [
                    '1w7OgIMMRc4' => "Sweet Child O' Mine",
                ],
            ],
            [
                'name' => 'Linkin Park',
                'slug' => 'linkin-park',
                'short_description' => 'Amerikaanse rockband die nu-metal, rap en elektronica combineert.',
                'biography' => "Linkin Park komt uit Agoura Hills, Californië, en brak in 2000 door met het album Hybrid Theory.\n\nDe band staat bekend om de combinatie van de zang van Chester Bennington en de raps van Mike Shinoda.",
                'bg_color' => '#22262b',
                'text_color' => '#e9ecef',
                'videos' => [
                    'kXYiU_JCYtU' => 'Numb',
                ],
            ],
            [
                'name' => 'Coldplay',
                'slug' => 'coldplay',
                'short_description' => 'Britse popband rond zanger Chris Martin.',
                'biography' => "Coldplay werd in 1996 in Londen opgericht door studenten van University College London.\n\nDe band groeide uit tot een van de grootste stadionacts ter wereld, met hits als Yellow, Clocks en Viva La Vida.",
                'bg_color' => '#fdf6e3',
                'text_color' => '#073642',
                'videos' => [
                    'dvgZkm1xWPE' => 'Viva La Vida',
                ],
            ],
            [
                'name' => 'Adele',
                'slug' => 'adele',
                'short_description' => 'Britse singer-songwriter uit Londen.',
                'biography' => "Adele Adkins brak in 2008 door met haar debuutalbum 19.\n\nMet albums als 21 en 25 werd ze een van de bestverkopende artiesten van haar generatie. Hello brak bij verschijning in 2015 meerdere streamingrecords.",
                'bg_color' => '#2b2d42',
                'text_color' => '#edf2f4',
                'videos' => [
                    'YQHsXMglC9A' => 'Hello',
                ],
            ],
            [
                'name' => 'Michael Jackson',
                'slug' => 'michael-jackson',
                'short_description' => 'De King of Pop.',
                'biography' => "Michael Jackson begon als kind bij The Jackson 5 en werd solo een van de grootste artiesten aller tijden.\n\nThriller (1982) is het bestverkochte album ooit.",
                'bg_color' => '#ffffff',
                'text_color' => '#111111',
                'videos' => [
                    'Zi_XLOBDo_Y' => 'Billie Jean',
                ],
            ],
            [
                'name' => 'Ed Sheeran',
                'slug' => 'ed-sheeran',
                'short_description' => 'Britse singer-songwriter met gitaar en loopstation.',
                'biography' => "Ed Sheeran groeide op in Suffolk en speelde jarenlang kleine optredens voordat hij in 2011 doorbrak met The A Team.\n\nHij staat bekend om zijn solo-optredens met alleen een gitaar en een loopstation.",
                'bg_color' => '#fff4e6',
                'text_color' => '#7c2d12',
                'videos' => [
                    'JGwWNGJdvx8' => 'Shape of You',
                ],
            ],
            [
                'name' => 'Mark Ronson',
                'slug' => 'mark-ronson',
                'short_description' => 'Britse producer en dj.',
                'biography' => "Mark Ronson produceerde onder andere Back to Black van Amy Winehouse.\n\nSamen met Bruno Mars scoorde hij in 2014 een wereldhit met Uptown Funk.",
                'bg_color' => '#ffd60a',
                'text_color' => '#1d1d1d',
                'videos' => [
                    'OPf0YbXqDm0' => 'Uptown Funk ft. Bruno Mars',
                ],
            ],
            [
                'name' => 'Rick Astley',
                'slug' => 'rick-astley',
                'short_description' => 'Britse zanger, bekend van Never Gonna Give You Up.',
                'biography' => "Rick Astley scoorde in 1987 een nummer 1-hit in 25 landen met Never Gonna Give You Up.\n\nDankzij de internetgrap \"rickrolling\" kent een nieuwe generatie het nummer ook.",
                'bg_color' => '#0b3d91',
                'text_color' => '#ffffff',
                'videos' => [
                    'dQw4w9WgXcQ' => 'Never Gonna Give You Up',
                ],
            ],
        ];
    }
}
