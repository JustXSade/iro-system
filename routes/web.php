<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // Add this line

// Dashboard Page - Now routes through the Controller
Route::get('/', [DashboardController::class, 'index']);


// Keep the other routes as they were for now
Route::get('/directory', function () {
    return view('directory');
});

Route::get('/history', function () {
    return view('history');
});

Route::get('/profile', function () {
    return view('profile');
});