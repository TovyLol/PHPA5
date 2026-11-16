<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBandUserRequest;
use App\Models\Band;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Beheerders van een band toevoegen en verwijderen (FO 3.3, TO 4.1).
 */
class BandUserController extends Controller
{
    /**
     * Geeft een bestaande gebruiker toegang tot de band.
     */
    public function store(StoreBandUserRequest $request, Band $band)
    {
        $user = User::where('email', $request->validated('email'))->firstOrFail();

        $band->users()->attach($user);

        return redirect()
            ->to(route('bands.edit', $band).'#beheerders')
            ->with('success', $user->name.' is nu beheerder van '.$band->name.'.');
    }

    /**
     * Trekt de toegang van een beheerder in, of laat de ingelogde gebruiker de band verlaten.
     * Door scopeBindings() in de route is $user gegarandeerd een beheerder van deze band.
     */
    public function destroy(Request $request, Band $band, User $user)
    {
        $this->authorize('manageUsers', $band);

        // Een band zonder beheerders kan door niemand meer worden aangepast
        if ($band->users()->count() <= 1) {
            return redirect()
                ->to(route('bands.edit', $band).'#beheerders')
                ->with('error', 'Een band moet minimaal één beheerder hebben. Voeg eerst iemand anders toe.');
        }

        $band->users()->detach($user);

        // Zelf vertrokken: je hebt geen toegang meer tot de beheerpagina
        if ($user->is($request->user())) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Je hebt '.$band->name.' verlaten.');
        }

        return redirect()
            ->to(route('bands.edit', $band).'#beheerders')
            ->with('success', $user->name.' is geen beheerder meer van '.$band->name.'.');
    }
}
