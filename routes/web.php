<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteMovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/movies', [FavoriteMovieController::class, 'store'])->name('movies.store');
    Route::put('/movies/{movie}', [FavoriteMovieController::class, 'update'])->name('movies.update');
});
