<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//overzicht dashboard whatever het laat je gw alles zien
class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $bands = $request->user()
            ->bands()
            ->withCount(['users', 'videos'])
            ->orderBy('name')
            ->get();

        return view('dashboard', compact('bands'));
    }
}
