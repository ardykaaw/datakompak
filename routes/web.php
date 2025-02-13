<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DailyUnitRecordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UnitMachineController;

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
    Route::get('/ikhtisar-harian', [DailyUnitRecordController::class, 'index'])->name('ikhtisar-harian');
    Route::post('/ikhtisar-harian', [DailyUnitRecordController::class, 'store'])->name('ikhtisar-harian.store');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // User Management (All authenticated users can view)
    Route::get('user-management', [UserManagementController::class, 'index'])->name('user-management.index');
    
    // User Management actions (Only for admin and super_admin)
    Route::middleware(['auth', 'can:manage-users'])->group(function () {
        Route::get('user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
        Route::post('user-management', [UserManagementController::class, 'store'])->name('user-management.store');
        Route::get('user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
        Route::put('user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
        Route::delete('user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });

    // Unit dan Mesin
    Route::resource('unit-mesin', UnitMachineController::class);
    Route::post('unit-mesin/{unit}/machines', [UnitMachineController::class, 'storeMachine'])->name('unit-mesin.machines.store');
});
