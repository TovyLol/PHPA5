<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Http\Request;

// openbare zoekpagina (FO 3.4, TO 4.1)
class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $bands = Band::query()
            ->when($q !== '', function ($query) use ($q) {
                // % en _ zijn jokers in LIKE, die escapen we zodat "100%" gewoon "100%" zoekt.
                // Expliciet ESCAPE '!' omdat MySQL en SQLite (tests) een ander standaard escape-teken hebben
                $term = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $q);
                $query->whereRaw("name LIKE ? ESCAPE '!'", ['%'.$term.'%']);
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString(); // ?q= blijft staan bij volgende pagina

        return view('search', compact('bands', 'q'));
    }
}
