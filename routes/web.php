<?php

use App\Http\Controllers\BandController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Na inloggen/registreren stuurt laravel/ui je naar /home, dat wordt nu het dashboard
Route::redirect('/home', '/dashboard')->name('home');

// Alleen voor ingelogde gebruikers (TO 7.1)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/bands/create', [BandController::class, 'create'])->name('bands.create');
    Route::post('/bands', [BandController::class, 'store'])->name('bands.store');
});
