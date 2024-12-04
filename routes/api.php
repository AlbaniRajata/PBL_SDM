<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAdminController;
use App\Http\Controllers\Api\JabatanKegiatanAdminController;

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

Route::post('/login', \App\Http\Controllers\Api\LoginController::class)->name('login');

Route::prefix('user-admin')->group(function () {
    Route::get('/users', [UserAdminController::class, 'index']);
    Route::get('/users/{id}', [UserAdminController::class, 'show']);
    Route::post('/users', [UserAdminController::class, 'store']);
    Route::put('/users/{id}', [UserAdminController::class, 'update']);
    Route::delete('/users/{id}', [UserAdminController::class, 'destroy']);
});
// Routes untuk Jabatan Kegiatan
Route::prefix('jabatan-kegiatan')->group(function () {
    Route::get('/', [JabatanKegiatanAdminController::class, 'index']);
    Route::post('/', [JabatanKegiatanAdminController::class, 'store']);
    Route::get('/{id}', [JabatanKegiatanAdminController::class, 'show']);
    Route::put('/{id}', [JabatanKegiatanAdminController::class, 'update']);
    Route::delete('/{id}', [JabatanKegiatanAdminController::class, 'destroy']);
});

