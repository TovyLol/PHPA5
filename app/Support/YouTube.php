<?php

namespace App\Support;

/**
 * Hulpfuncties voor YouTube-links (TO 4.4).
 */
class YouTube
{
    /** Domeinen die we als YouTube accepteren. */
    private const HOSTS = [
        'youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com',
        'youtu.be', 'www.youtu.be',
        'youtube-nocookie.com', 'www.youtube-nocookie.com',
    ];

    /**
     * Haalt het video-ID (11 tekens) uit een YouTube-link, of geeft null als het geen geldige link is.
     *
     * Ondersteunt onder andere:
     *   https://www.youtube.com/watch?v=dQw4w9WgXcQ
     *   https://youtu.be/dQw4w9WgXcQ?si=abc
     *   https://www.youtube.com/shorts/dQw4w9WgXcQ
     *   https://www.youtube.com/embed/dQw4w9WgXcQ
     *   youtube.com/watch?v=dQw4w9WgXcQ   (zonder https://)
     */
    public static function id(string $url): ?string
    {
        $url = trim($url);

        // Link zonder http(s):// toch kunnen lezen
        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (! in_array($host, self::HOSTS, true)) {
            return null;
        }

        $path = $parts['path'] ?? '';

        if (str_contains($host, 'youtu.be')) {
            // youtu.be/ID
            $id = explode('/', ltrim($path, '/'))[0];
        } elseif (preg_match('#^/(shorts|embed|live|v)/([^/?]+)#', $path, $m)) {
            // youtube.com/shorts/ID, /embed/ID, /live/ID
            $id = $m[2];
        } else {
            // youtube.com/watch?v=ID
            parse_str($parts['query'] ?? '', $query);
            $id = $query['v'] ?? '';
        }

        return is_string($id) && preg_match('/^[A-Za-z0-9_-]{11}$/', $id) ? $id : null;
    }

    /** Miniatuurafbeelding van een video. */
    public static function thumbnail(string $id): string
    {
        return 'https://i.ytimg.com/vi/'.$id.'/mqdefault.jpg';
    }
}
