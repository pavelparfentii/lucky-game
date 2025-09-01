<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Головна сторінка з формою реєстрації
Route::get('/', [HomeController::class, 'index'])->name('home');

// Обробка форми реєстрації
Route::post('/register', [HomeController::class, 'register'])->name('register');
