<?php

use App\Http\Controllers\BandController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// redirect /home to /dashboard
Route::redirect('/home', '/dashboard')->name('home');
// geen gasten
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/bands/create', [BandController::class, 'create'])->name('bands.create');
    Route::post('/bands', [BandController::class, 'store'])->name('bands.store');
    Route::get('/bands/{band}/edit', [BandController::class, 'edit'])->name('bands.edit');
    Route::put('/bands/{band}', [BandController::class, 'update'])->name('bands.update');
});
