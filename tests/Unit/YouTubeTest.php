<?php

namespace Tests\Unit;

use App\Support\YouTube;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Het video-ID uit verschillende soorten YouTube-links halen (TO 4.4).
 */
class YouTubeTest extends TestCase
{
    public static function geldigeLinks(): array
    {
        return [
            'watch' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            'watch met extra parameters' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=42s&list=abc'],
            'zonder www' => ['https://youtube.com/watch?v=dQw4w9WgXcQ'],
            'mobiel' => ['https://m.youtube.com/watch?v=dQw4w9WgXcQ'],
            'korte link' => ['https://youtu.be/dQw4w9WgXcQ'],
            'korte link met si' => ['https://youtu.be/dQw4w9WgXcQ?si=abcdef'],
            'shorts' => ['https://www.youtube.com/shorts/dQw4w9WgXcQ'],
            'embed' => ['https://www.youtube.com/embed/dQw4w9WgXcQ'],
            'zonder https' => ['youtube.com/watch?v=dQw4w9WgXcQ'],
            'met spaties' => ['  https://youtu.be/dQw4w9WgXcQ  '],
        ];
    }

    public static function ongeldigeLinks(): array
    {
        return [
            'leeg' => [''],
            'geen link' => ['hallo'],
            'andere site' => ['https://vimeo.com/123456789'],
            'nep-domein' => ['https://youtube.com.evil.com/watch?v=dQw4w9WgXcQ'],
            'geen video-id' => ['https://www.youtube.com/'],
            'te kort id' => ['https://youtu.be/abc'],
            'kanaal' => ['https://www.youtube.com/@artiest'],
        ];
    }

    #[DataProvider('geldigeLinks')]
    public function test_haalt_id_uit_geldige_link(string $url): void
    {
        $this->assertSame('dQw4w9WgXcQ', YouTube::id($url));
    }

    #[DataProvider('ongeldigeLinks')]
    public function test_geeft_null_bij_ongeldige_link(string $url): void
    {
        $this->assertNull(YouTube::id($url));
    }
}
