<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBandRequest;
use App\Models\Band;

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
}
