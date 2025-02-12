<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\IkhtisarHarianController;

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
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Ikhtisar Harian
    Route::get('/ikhtisar-harian', [IkhtisarHarianController::class, 'index'])->name('ikhtisar-harian');
    Route::post('/ikhtisar-harian', [DataController::class, 'store'])->name('ikhtisar-harian.store');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});

// Super Admin Routes
Route::middleware(['auth', 'super.admin'])->group(function () {
    Route::get('/super-admin', [DashboardController::class, 'superAdmin'])->name('super.admin');
});
