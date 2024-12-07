<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAdminController;
use App\Http\Controllers\Api\JabatanKegiatanAdminController;
use App\Http\Controllers\Api\KegiatanAdminController;
use App\Http\Controllers\Api\StatistikAdminController;
use App\Http\Controllers\Api\DashboardAdminController;
use App\Http\Controllers\Api\UserPimpinanController;
use App\Http\Controllers\Api\KegiatanPimpinanController;

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

//Routes untuk User Admin
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
// Routes untuk Kegiatan Admin
Route::prefix('kegiatan-admin')->group(function () {
    Route::get('/', [KegiatanAdminController::class, 'index']);
    Route::get('/{id}', [KegiatanAdminController::class, 'show']);
    Route::post('/', [KegiatanAdminController::class, 'store']);
    Route::put('/{id}', [KegiatanAdminController::class, 'update']);
    Route::delete('/{id}', [KegiatanAdminController::class, 'destroy']);
    Route::get('/data/dosen', [KegiatanAdminController::class, 'getDosen']);
    Route::get('/data/jabatan', [KegiatanAdminController::class, 'getJabatan']);
});
// Routes untuk Statistik Admin
Route::prefix('statistik-admin')->group(function () {
    Route::get('/', [StatistikAdminController::class, 'index']);
});
// Routes untuk Dashboard Admin
Route::prefix('dashboard-admin')->group(function () {
    Route::get('/total-dosen', [DashboardAdminController::class, 'getTotalDosen']);
    Route::get('/total-kegiatan-jti', [DashboardAdminController::class, 'getTotalKegiatanJTI']);
    Route::get('/total-kegiatan-non-jti', [DashboardAdminController::class, 'getTotalKegiatanNonJTI']);
    Route::get('/kalender', [DashboardAdminController::class, 'getKalender']);
});

//Routes untuk User Pimpinan
Route::prefix('user-pimpinan')->group(function () {
    Route::get('/', [UserPimpinanController::class, 'getAllDosen']);
    Route::get('/detail/{id}', [UserPimpinanController::class, 'getDosenDetail']);
    Route::post('/search', [UserPimpinanController::class, 'searchDosen']);
});
// Routes untuk Kegiatan Pimpinan
Route::prefix('kegiatan-pimpinan')->group(function () {
    Route::get('/', [KegiatanPimpinanController::class, 'index']);
    Route::get('/{id}', [KegiatanPimpinanController::class, 'show']);
    Route::get('/data/dosen', [KegiatanPimpinanController::class, 'getDosen']);
    Route::get('/data/jabatan', [KegiatanPimpinanController::class, 'getJabatan']);
});
// Routes untuk Statistik Pimpinan
Route::prefix('statistik-pimpinan')->group(function () {
    Route::get('/', [StatistikAdminController::class, 'index']);
});