<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Головна сторінка з формою реєстрації
Route::get('/', [HomeController::class, 'index'])->name('home');

// Обробка форми реєстрації
Route::post('/register', [HomeController::class, 'register'])->name('register');


Route::middleware('check.link')->group(function () {
    // Link management routes
    Route::get('/link/{token}', [LinkController::class, 'show'])->name('link.show');
    Route::post('/link/{token}/regenerate', [LinkController::class, 'regenerate'])->name('link.regenerate');
    Route::post('/link/{token}/deactivate', [LinkController::class, 'deactivate'])->name('link.deactivate');
    
    // Game routes
    Route::post('/link/{token}/play', [GameController::class, 'play'])->name('link.play');
    Route::get('/link/{token}/history', [GameController::class, 'history'])->name('link.history');
});
