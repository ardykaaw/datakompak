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
use App\Http\Controllers\IkhtisarHarianController;
use App\Http\Controllers\UnitMesinController;

// Redirect root to login
Route::redirect('/', '/login');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware', 'auth'),
    'verified'
])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Ikhtisar Harian
    Route::get('/ikhtisar-harian', [IkhtisarHarianController::class, 'index'])->name('ikhtisar-harian');
    Route::post('/ikhtisar-harian/store', [IkhtisarHarianController::class, 'store'])->name('ikhtisar-harian.store');
    Route::get('/ikhtisar-harian/view', [IkhtisarHarianController::class, 'view'])->name('ikhtisar-harian.view');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // User Management
    Route::prefix('user-management')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('user-management.create');
        Route::post('/', [UserManagementController::class, 'store'])->name('user-management.store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });
    
    // Unit dan Mesin routes
    Route::prefix('unit-mesin')->group(function () {
        // Letakkan route mesin dan unit di atas
        Route::get('/mesin', [UnitMachineController::class, 'mesinIndex'])->name('unit-mesin.mesin');
        Route::get('/unit', [UnitMachineController::class, 'unitIndex'])->name('unit-mesin.unit');
        
        // Route resource
        Route::get('/', [UnitMachineController::class, 'index'])->name('unit-mesin.index');
        Route::get('/create', [UnitMachineController::class, 'create'])->name('unit-mesin.create');
        Route::post('/', [UnitMachineController::class, 'store'])->name('unit-mesin.store');
        Route::get('/{unitMesin}', [UnitMachineController::class, 'show'])->name('unit-mesin.show');
        Route::get('/{unitMesin}/edit', [UnitMachineController::class, 'edit'])->name('unit-mesin.edit');
        Route::put('/{unitMesin}', [UnitMachineController::class, 'update'])->name('unit-mesin.update');
        Route::delete('/{unitMesin}', [UnitMachineController::class, 'destroy'])->name('unit-mesin.destroy');
        
        // Route untuk machines
        Route::post('/{unit}/machines', [UnitMachineController::class, 'storeMachine'])->name('unit-mesin.machines.store');
        Route::delete('/{unit}/machines/{machine}', [UnitMachineController::class, 'destroyMachine'])->name('unit-mesin.machines.destroy');
    });
});
