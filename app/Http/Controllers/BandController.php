<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBandRequest;
use App\Http\Requests\UpdateBandRequest;
use App\Models\Band;
use Illuminate\Support\Facades\Storage;

// aanmakenen en koppelen
class BandController extends Controller
{
    // form voor een nieuwe band
    public function create()
    {
        $this->authorize('create', Band::class);

        return view('bands.create');
    }

    // sla band op -> koppel gebruiker
    public function store(StoreBandRequest $request)
    {
        $band = Band::create($request->validated());

        $band->users()->attach($request->user());

        return redirect()
            ->route('dashboard')
            ->with('success', 'De band "'.$band->name.'" is aangemaakt.');
    }

   // beheer pagina
    public function edit(Band $band)
    {
        $this->authorize('update', $band);

        return view('bands.edit', compact('band'));
    }

    // update
    public function update(UpdateBandRequest $request, Band $band)
    {
        $data = $request->safe()->except(['photo', 'remove_photo']);

        // foto functionaliteit
        if ($request->hasFile('photo')) {
            $this->deletePhoto($band); // check of er al een foto is
            $data['photo_path'] = $request->file('photo')->store('bands', 'public');
        } elseif ($request->boolean('remove_photo')) {
            $this->deletePhoto($band);// verwijder oude foto
            $data['photo_path'] = null;
        }

        $band->update($data);
        // initial update
        return redirect()
            ->route('bands.edit', $band)
            ->with('success', 'De wijzigingen zijn opgeslagen.');
    }

    //verwijder foto uit server
    private function deletePhoto(Band $band): void
    {
        if ($band->photo_path) {
            Storage::disk('public')->delete($band->photo_path);
        }
    }
}
