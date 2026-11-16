<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Band;
use App\Models\Video;
use App\Support\YouTube;

/**
 * Toevoegen en verwijderen van YouTube-video's bij een band (TO 7.2).
 */
class VideoController extends Controller
{
    /**
     * Voegt een video toe. Het video-ID wordt uit de link gehaald voor de embed op de EPK-pagina.
     */
    public function store(StoreVideoRequest $request, Band $band)
    {
        $band->videos()->create([
            'youtube_url' => trim($request->validated('youtube_url')),
            'youtube_id' => YouTube::id($request->validated('youtube_url')),
            'title' => $request->validated('title'),
        ]);

        return redirect()
            ->to(route('bands.edit', $band).'#videos')
            ->with('success', 'De video is toegevoegd.');
    }

    /**
     * Verwijdert een video. Door scopeBindings() in de route hoort de video gegarandeerd bij deze band.
     */
    public function destroy(Band $band, Video $video)
    {
        $this->authorize('update', $band);

        $video->delete();

        return redirect()
            ->to(route('bands.edit', $band).'#videos')
            ->with('success', 'De video is verwijderd.');
    }
}
