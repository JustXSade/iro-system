<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // Add this line

// Dashboard Page - Now routes through the Controller
Route::get('/', [DashboardController::class, 'index']);

Route::post('/store-entry', [DashboardController::class, 'store']);

// Directory Pages & APIs
Route::get('/directory', [DashboardController::class, 'directoryIndex']);
Route::get('/api/players/{id}/details', [DashboardController::class, 'playerDetails']);
Route::post('/settle-debt', [DashboardController::class, 'settleDebt']);

Route::get('/history', function () {
    return view('history');
});

Route::get('/profile', function () {
    return view('profile');
});

// Player Search Route for Autocomplete
Route::get('/players/search', [DashboardController::class, 'searchPlayers']);