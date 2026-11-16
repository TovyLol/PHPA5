<?php

namespace App\Http\Controllers;

use App\Models\Band;
// openbare band pagina
class EpkController extends Controller
{
    public function show(Band $band)
    {   // laad videos in van band
        $band->load(['videos' => fn ($query) => $query->oldest()]);

        return view('epk.show', compact('band'));
    }
}
