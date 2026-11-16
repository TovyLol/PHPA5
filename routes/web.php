<?php

use App\Http\Controllers\BandController;
use App\Http\Controllers\BandUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EpkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// Openbaar: zoeken en EPK-pagina's (ook voor gasten)
Route::get('/', SearchController::class)->name('search');
Route::get('/epk/{band:slug}', [EpkController::class, 'show'])->name('epk.show');

// Inloggen, registreren en uitloggen. Geen e-mailfuncties: geen wachtwoord-reset en geen e-mailverificatie
Auth::routes(['reset' => false, 'verify' => false]);

// redirect /home to /dashboard
Route::redirect('/home', '/dashboard')->name('home');
// geen gasten
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/bands/create', [BandController::class, 'create'])->name('bands.create');
    Route::post('/bands', [BandController::class, 'store'])->name('bands.store');
    Route::get('/bands/{band}/edit', [BandController::class, 'edit'])->name('bands.edit');
    Route::put('/bands/{band}', [BandController::class, 'update'])->name('bands.update');
    Route::delete('/bands/{band}', [BandController::class, 'destroy'])->name('bands.destroy');

    // eigen profiel
    Route::get('/profiel', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profiel/wachtwoord', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Video's van een band. scopeBindings(): de video moet bij die band horen, anders 404
    Route::post('/bands/{band}/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::delete('/bands/{band}/videos/{video}', [VideoController::class, 'destroy'])
        ->scopeBindings()
        ->name('videos.destroy');

    // Beheerders van een band. scopeBindings(): de gebruiker moet beheerder van die band zijn, anders 404
    Route::post('/bands/{band}/users', [BandUserController::class, 'store'])->name('bands.users.store');
    Route::delete('/bands/{band}/users/{user}', [BandUserController::class, 'destroy'])
        ->scopeBindings()
        ->name('bands.users.destroy');
});
