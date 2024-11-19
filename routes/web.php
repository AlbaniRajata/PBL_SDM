<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\StatistikController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', [DashboardController::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::post('user/create_ajax', [UserController::class, 'create_ajaxAdmin'])->name('admin.user.create_ajax');
    Route::post('user/ajax', [UserController::class, 'store_ajaxAdmin'])->name('admin.user.store_ajax');
    Route::get('user/{id}/show_ajax', [UserController::class, 'show_ajaxAdmin'])->name('admin.user.show_ajax');
    Route::get('user/{id}/edit_ajax', [UserController::class, 'edit_ajaxAdmin'])->name('admin.user.edit_ajax');
    Route::get('user/{id}/delete_ajax', [UserController::class, 'delete_ajaxAdmin'])->name('admin.user.delete_ajax');
});

// Route admin
// Index
Route::get('/admin/kegiatan',[KegiatanController::class, 'admin']);
Route::get('/admin/statistik',[StatistikController::class, 'admin']);
Route::get('/admin/user', [UserController::class, 'admin']);
Route::post('/admin/user/list', [UserController::class, 'list'])->name('admin.user.list');
Route::post('/pimpinan/user/list', [UserController::class, 'list'])->name('pimpinan.user.list');
Route::get('/admin/user/export_pdf', [UserController::class, 'exportPdf'])->name('admin.user.export_pdf');
//create
Route::post('/', [KegiatanController::class, 'storeAdmin']);              // Store new kegiatan data
Route::get('/create_ajax', [KegiatanController::class, 'create_ajax']); // Display form for adding kegiatan via AJAX
Route::post('/ajax', [KegiatanController::class, 'store_ajax']);     // Store new kegiatan data via AJAX
//user
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'admin'])->name('user.index');
    Route::post('/pimpinan', [UserController::class, 'pimpinan'])->name('user.pimpinan');
    // Route::get('admin/user', [UserController::class, 'list'])->name('admin.user.list');
    Route::get('/export_pdf', [UserController::class, 'exportPdf'])->name('user.export_pdf');
    // Route::get('/admin/user/{id}/show_ajax', [UserController::class, 'show_ajaxAdmin'])->name('admin.user.show_ajax');
    Route::get('/{id}/edit_ajax', [UserController::class, 'editAjax'])->name('user.edit_ajax');
    Route::get('/{id}/delete_ajax', [UserController::class, 'deleteAjax'])->name('user.delete_ajax'); 
});
Route::prefix('kegiatan')->group(function () {

    Route::post('/pimpinan/kegiatan/list', [KegiatanController::class, 'list'])->name('pimpinan.kegiatan.list');
    Route::post('/dosenPIC/kegiatan/list', [KegiatanController::class, 'list'])->name('pimpinan.kegiatan.list');

    Route::get('/admin/kegiatan/{id}/edit_ajax', [KegiatanController::class, 'editAjaxAdmin'])->name('admin.kegiatan.edit_ajax');
    Route::get('/admin/kegiatan/{id}/delete_ajax', [KegiatanController::class, 'deleteAjaxAdmin'])->name('admin.kegiatan.delete_ajax');
});

Route::prefix('admin')->group(function () {
    Route::prefix('kegiatan')->group(function () {
        Route::post('/admin/kegiatan/list', [KegiatanController::class, 'list'])->name('admin.kegiatan.list');
        Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxAdmin'])->name('admin.kegiatan.show_ajax');
        Route::get('/create_ajax', [KegiatanController::class, 'create_ajaxAdmin'])->name('admin.kegiatan.ajax');
        Route::post('/ajax', [KegiatanController::class, 'storeAdmin'])->name('admin.storeAjax');
        Route::get('/admin/kegiatan/export_pdf', [KegiatanController::class, 'exportPdf'])->name('admin.kegiatan.export_pdf');
    });


});

//Route pimpinan
// Index
Route::get('/pimpinan/statistik',[StatistikController::class, 'pimpinan']);
Route::get('pimpinan/user', [UserController::class, 'pimpinan']);

//Route dosenPIC
// Index
Route::get('/dosenPIC/kegiatan',[KegiatanController::class, 'dosenPIC']);
Route::get('/dosenPIC/statistik',[StatistikController::class, 'dosenPIC']);
Route::get('dosenPIC/user', [UserController::class, 'dosenPIC']);

//Route dosenAnggota
// Index
Route::get('/dosenAnggota/kegiatan',[KegiatanController::class, 'dosenAnggota']);
Route::get('/dosenAnggota/statistik',[StatistikController::class, 'dosenAnggota']);
