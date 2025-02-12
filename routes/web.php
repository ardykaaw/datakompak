<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;

// Redirect root to login
Route::redirect('/', '/login');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/data', [DataController::class, 'store'])->name('data.store');
});

// Super Admin Routes
Route::middleware(['auth', 'super.admin'])->group(function () {
    Route::get('/super-admin', [DashboardController::class, 'superAdmin'])->name('super.admin');
});
