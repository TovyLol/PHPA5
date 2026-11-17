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

    public static function id(string $url): ?string
    {
        $url = trim($url);

        // Link zonder http toch lezen
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

    
    public static function thumbnail(string $id): string
    {
        return 'https://i.ytimg.com/vi/'.$id.'/mqdefault.jpg';
    }
}
