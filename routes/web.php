<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\JabatanKegiatanController;

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
Route::get('/api/kegiatan/events', [KegiatanController::class, 'getEvents']);
Route::get('/', [DashboardController::class, 'index']);


Route::prefix('admin')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'admin']);
        Route::post('/list', [UserController::class, 'list'])->name('admin.user.list');
        Route::get('/create_ajax', [UserController::class, 'create_ajaxAdmin'])->name('admin.user.ajax');
        Route::post('/ajax', [UserController::class, 'store_ajaxAdmin'])->name('admin.user.store_ajax');
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajaxAdmin'])->name('admin.user.show_ajax');
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajaxAdmin'])->name('admin.user.edit_ajax');
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajaxAdmin'])->name('admin.user.update_ajax');
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajaxAdmin'])->name('admin.user.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajaxAdmin'])->name('admin.user.delete_ajax');
        Route::get('/export_pdf', [UserController::class, 'exportPdf'])->name('admin.user.export_pdf');
        Route::get('/export_excel', [UserController::class, 'exportExcel'])->name('admin.user.export_excel');
        Route::get('/{id}/export_word', [KegiatanController::class, 'exportWord'])->name('admin.kegiatan.export_word');
    });
});

Route::prefix('admin')->group(function () {
    Route::prefix('kegiatan')->group(function () {
        Route::get('/',[KegiatanController::class, 'admin']);
        Route::post('/kegiatan/list', [KegiatanController::class, 'list'])->name('admin.kegiatan.list');
        Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxAdmin'])->name('admin.kegiatan.show_ajax');
        Route::get('/create_ajax', [KegiatanController::class, 'create_ajaxAdmin'])->name('admin.kegiatan.create_ajax');
        Route::post('/ajax', [KegiatanController::class, 'storeAdmin'])->name('admin.storeAjax');
        Route::get('/{id}/edit_ajax', [KegiatanController::class, 'editAjaxAdmin'])->name('admin.kegiatan.edit_ajax');
        Route::put('/{id}/update_ajax', [KegiatanController::class, 'updateAjaxAdmin'])->name('admin.kegiatan.update_ajax');
        Route::get('/{id}/delete_ajax', [KegiatanController::class, 'deleteAjaxAdmin'])->name('admin.kegiatan.delete_ajax');
        Route::get('/export_pdf', [KegiatanController::class, 'exportPdf'])->name('admin.kegiatan.export_pdf');
        Route::get('/{id}/export_word', [KegiatanController::class, 'exportWord'])->name('admin.kegiatan.export_word');
    });
});

Route::prefix('admin')->group(function () {
    Route::prefix('jabatan')->group(function () {
        Route::get('/', [JabatanKegiatanController::class, 'index'])->name('admin.jabatan.index');
        Route::post('/list', [JabatanKegiatanController::class, 'list'])->name('admin.jabatan.list');
        Route::get('/create_ajax', [JabatanKegiatanController::class, 'create_ajax'])->name('admin.jabatan.create_ajax');
        Route::post('/ajax', [JabatanKegiatanController::class, 'store_ajax'])->name('admin.jabatan.store_ajax');
        Route::get('/{id}/edit_ajax', [JabatanKegiatanController::class, 'edit_ajax'])->name('admin.jabatan.edit_ajax');
        Route::put('/{id}/update_ajax', [JabatanKegiatanController::class, 'update_ajax'])->name('admin.jabatan.update_ajax');
        Route::get('/{id}/delete_ajax', [JabatanKegiatanController::class, 'confirm_ajax'])->name('admin.jabatan.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [JabatanKegiatanController::class, 'delete_ajax'])->name('admin.jabatan.delete_ajax');
    });
});

Route::prefix('admin')->group(function () {
    Route::prefix('statistik')->group(function () {
        Route::get('/', [StatistikController::class, 'admin']);
        Route::post('/list', [StatistikController::class, 'list'])->name('admin.statistik.list');
        Route::get('/create_ajax', [StatistikController::class, 'create_ajax'])->name('admin.statistik.create_ajax');
        Route::post('/ajax', [StatistikController::class, 'store_ajax'])->name('admin.statistik.store_ajax');
        Route::get('/{id}/edit_ajax', [StatistikController::class, 'edit_ajax'])->name('admin.statistik.edit_ajax');
        Route::put('/{id}/update_ajax', [StatistikController::class, 'update_ajax'])->name('admin.statistik.update_ajax');
        Route::get('/{id}/delete_ajax', [StatistikController::class, 'confirm_ajax'])->name('admin.statistik.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [StatistikController::class, 'delete_ajax'])->name('admin.statistik.delete_ajax');
    });
});

Route::prefix('admin')->group(function () {
    Route::prefix('jenispengguna')->group(function () {
        Route::get('/', [UserController::class, 'levelAdmin'])->name('admin.jenispengguna.index');
    });
});

//Route pimpinan
// Index
Route::get('/pimpinan/statistik',[StatistikController::class, 'pimpinan']);
Route::get('pimpinan/user', [UserController::class, 'pimpinan']);
Route::post('/pimpinan/user/list', [UserController::class, 'list'])->name('pimpinan.user.list');
Route::post('/pimpinan', [UserController::class, 'pimpinan'])->name('user.pimpinan');
Route::post('/pimpinan/kegiatan/list', [KegiatanController::class, 'list'])->name('pimpinan.kegiatan.list');

//Route dosenPIC
// Index
Route::get('/dosenPIC/kegiatan',[KegiatanController::class, 'dosenPIC']);
Route::get('/dosenPIC/statistik',[StatistikController::class, 'dosenPIC']);
Route::get('dosenPIC/user', [UserController::class, 'dosenPIC']);
Route::post('/dosenPIC/kegiatan/list', [KegiatanController::class, 'list'])->name('pimpinan.kegiatan.list');

//Route dosenAnggota
// Index
Route::get('/dosenAnggota/kegiatan',[KegiatanController::class, 'dosenAnggota']);
Route::get('/dosenAnggota/statistik',[StatistikController::class, 'dosenAnggota']);
