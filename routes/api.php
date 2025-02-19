<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Unit;
use App\Http\Controllers\LaporanKesiapanKitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/units/{unit}/machines', function(App\Models\Unit $unit) {
    return response()->json($unit->machines)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET')
        ->header('Access-Control-Allow-Headers', 'Content-Type');
})->name('api.units.machines');

Route::get('/machines/last-data', function () {
    return App\Models\Machine::with(['logs' => function($query) {
        $query->latest('input_time')->limit(1);
    }])->get();
});

Route::post('/machines/last-data', [LaporanKesiapanKitController::class, 'getLastData'])->name('api.machines.last-data'); 