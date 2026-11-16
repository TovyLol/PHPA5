<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

//eigen gegevens wijzigen
class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->loadCount('bands');

        return view('profile.edit', compact('user'));
    }

    // naam en e-mailadres
    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Je gegevens zijn opgeslagen.');
    }

    // wachtwoord, de 'hashed' cast in User hasht het automatisch
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->update(['password' => $request->validated('password')]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Je wachtwoord is gewijzigd.');
    }
}
