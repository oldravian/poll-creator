<?php

use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [PollController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Poll routes (for future implementation)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('polls', PollController::class)->except(['index']);
});

// Public poll routes (for voting)
Route::get('poll/{poll:slug}', [PollController::class, 'show'])->name('polls.public');

require __DIR__.'/auth.php';