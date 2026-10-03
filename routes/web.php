<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Main Dashboard එක
Route::get('/', [DashboardController::class, 'index']);