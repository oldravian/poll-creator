<?php

use App\Http\Controllers\PollController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');


// Poll API routes (session auth without CSRF)
Route::middleware(['web', 'auth', 'verified'])->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    \App\Http\Middleware\VerifyCsrfToken::class
])->group(function () {
    Route::post('/polls', [PollController::class, 'store']);
    Route::get('/polls', [PollController::class, 'apiIndex']);
    Route::get('/polls/{poll:slug}', [PollController::class, 'apiShow']);
    Route::put('/polls/{poll}', [PollController::class, 'update']);
    Route::delete('/polls/{poll}', [PollController::class, 'destroy']);
});

// Public poll routes (for voting) - no auth required, no CSRF
Route::middleware(['web'])->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    \App\Http\Middleware\VerifyCsrfToken::class
])->group(function () {
    Route::get('/polls/{poll:slug}/public', [PollController::class, 'publicShow']);
    Route::post('/polls/{poll:slug}/vote', [PollController::class, 'vote']);
});