<?php

use Illuminate\Support\Facades\Route;

// 1. Dashboard Page (Base URL)
Route::get('/', function () {
    return view('dashboard');
});

// 2. Directory Page (Players / Naya list)
Route::get('/directory', function () {
    return view('directory');
});

// 3. History Page (Past records)
Route::get('/history', function () {
    return view('history');
});

// 4. Profile Page (Settings/Logout)
Route::get('/profile', function () {
    return view('profile');
});