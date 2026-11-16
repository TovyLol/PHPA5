<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Band;
use App\Models\Video;
use App\Support\YouTube;

// voeg toe of verwijder videos van een band
class VideoController extends Controller
{
   //voeg de video echt toe
    public function store(StoreVideoRequest $request, Band $band)
    {
        $band->videos()->create([
            'youtube_url' => trim($request->validated('youtube_url')),
            'youtube_id' => YouTube::id($request->validated('youtube_url')),
            'title' => $request->validated('title'),
        ]);
        //success
        return redirect()
            ->to(route('bands.edit', $band).'#videos')
            ->with('success', 'De video is toegevoegd.');
    }

   // volgens mij maakt de naam het wel duidelijk wat er gebeurt
    public function destroy(Band $band, Video $video)
    {
        $this->authorize('update', $band);

        $video->delete();

        return redirect()
            ->to(route('bands.edit', $band).'#videos')
            ->with('success', 'De video is verwijderd.');
    }
}
