<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBandUserRequest;
use App\Models\Band;
use App\Models\User;
use Illuminate\Http\Request;

/* beheer bands kwa users en beheerders
 */
class BandUserController extends Controller
{
    /* give access
     */
    public function store(StoreBandUserRequest $request, Band $band)
    {
        $user = User::where('email', $request->validated('email'))->firstOrFail();

        $band->users()->attach($user);

        return redirect()
            ->to(route('bands.edit', $band).'#beheerders')
            ->with('success', $user->name.' is nu beheerder van '.$band->name.'.');
    }

    /* trek toegang in van gebruikers
     */
    public function destroy(Request $request, Band $band, User $user)
    {
        $this->authorize('manageUsers', $band);

        // check of er nog minimaal 1 beheerder overblijft
        if ($band->users()->count() <= 1) {
            return redirect()
                ->to(route('bands.edit', $band).'#beheerders')
                ->with('error', 'Een band moet minimaal één beheerder hebben. Voeg eerst iemand anders toe.');
        }

        $band->users()->detach($user);

        // dit moet je echt zelfweten man 
        if ($user->is($request->user())) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Je hebt '.$band->name.' verlaten.');
        }
        // success message 
        return redirect()
            ->to(route('bands.edit', $band).'#beheerders')
            ->with('success', $user->name.' is geen beheerder meer van '.$band->name.'.');
    }
}
