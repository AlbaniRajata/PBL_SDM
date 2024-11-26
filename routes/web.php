<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\JabatanKegiatanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ContactController;

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

Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/api/kegiatan/events', [KegiatanController::class, 'getEvents']);



// Route::get('/', [DashboardController::class, 'index']);
Route::middleware('auth')->group(function () {

    Route::middleware('authorize:admin,dosen,pimpinan')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['authorize:admin']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'admin']);
            Route::post('/list', [UserController::class, 'listAdmin'])->name('admin.user.list');
            Route::get('/create_ajax', [UserController::class, 'create_ajaxAdmin'])->name('admin.user.ajax');
            Route::post('/ajax', [UserController::class, 'store_ajaxAdmin'])->name('admin.user.store_ajax');
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajaxAdmin'])->name('admin.user.show_ajax');
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajaxAdmin'])->name('admin.user.edit_ajax');
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajaxAdmin'])->name('admin.user.update_ajax');
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajaxAdmin'])->name('admin.user.confirm_ajax');
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajaxAdmin'])->name('admin.user.delete_ajax');
            Route::get('/export_pdf', [UserController::class, 'exportPdf'])->name('admin.user.export_pdf');
            Route::get('/export_excel', [UserController::class, 'exportExcel'])->name('admin.user.export_excel');
        });

        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'admin']);
            Route::post('/list', [KegiatanController::class, 'listAdmin'])->name('admin.kegiatan.list');
            Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxAdmin'])->name('admin.kegiatan.show_ajax');
            Route::get('/create_ajax', [KegiatanController::class, 'create_ajaxAdmin'])->name('admin.kegiatan.create_ajax');
            Route::post('/ajax', [KegiatanController::class, 'storeAdmin'])->name('admin.storeAjax');
            Route::get('/{id}/edit_ajax', [KegiatanController::class, 'editAjaxAdmin'])->name('admin.kegiatan.edit_ajax');
            Route::put('/{id}/confirm_ajax', [KegiatanController::class, 'confirm_ajaxAdmin'])->name('admin.kegiatan.confirm_ajax');
            Route::put('/{id}/update_ajax', [KegiatanController::class, 'updateAjaxAdmin'])->name('admin.kegiatan.update_ajax');
            Route::get('/{id}/delete_ajax', [KegiatanController::class, 'delete_ajaxAdmin'])->name('admin.kegiatan.delete_ajax');
            Route::get('/export_pdf', [KegiatanController::class, 'exportPdf'])->name('admin.kegiatan.export_pdf');
            Route::get('/{id}/export_word', [KegiatanController::class, 'exportWord'])->name('admin.kegiatan.export_word');
            Route::post('/{id}/upload_surat_tugas', [KegiatanController::class, 'uploadSuratTugas'])->name('admin.kegiatan.upload_surat_tugas');
        });

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

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'admin']);
            Route::post('/list', [StatistikController::class, 'list'])->name('admin.statistik.list');
        });

        Route::prefix('jenispengguna')->group(function () {
            Route::get('/', [UserController::class, 'levelAdmin'])->name('admin.jenispengguna.index');
        });
    });

    Route::group(['prefix' => 'profil'], function () {
        Route::get('/', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/{id}', [ProfilController::class, 'update'])->name('profil.update');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil/{id}', [ProfilController::class, 'update'])->name('profil.update');
        Route::post('/profil/upload', [ProfilController::class, 'uploadProfileImage'])->name('profil.upload');
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index')->middleware('auth');
    });


    Route::group(['prefix' => 'pimpinan', 'middleware' => ['authorize:pimpinan']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'pimpinan']);
            Route::post('/list', [UserController::class, 'listPimpinan'])->name('pimpinan.user.list');
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajaxPimpinan'])->name('pimpinan.user.show_ajax');
        });

        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'pimpinan']);
            Route::post('/list', [KegiatanController::class, 'listPimpinan'])->name('pimpinan.kegiatan.list');
            Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxPimpinan'])->name('pimpinan.kegiatan.show_ajax');
        });

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'pimpinan']);
            Route::post('/list', [StatistikController::class, 'list'])->name('pimpinan.statistik.list');
        });
    });

    Route::group(['prefix' => 'dosen', 'middleware' => ['authorize:dosen']], function () {
        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'dosen']);
            Route::post('/list', [KegiatanController::class, 'listDosen'])->name('dosen.kegiatan.list');
            Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxDosen'])->name('dosen.kegiatan.show_ajax');
            Route::get('/create_ajax', [KegiatanController::class, 'create_ajaxDosen'])->name('dosen.kegiatan.create_ajax');
            Route::post('/ajax', [KegiatanController::class, 'storeDosen'])->name('dosen.storeAjax');
            Route::get('/{id}/edit_ajax', [KegiatanController::class, 'editAjaxDosen'])->name('dosen.kegiatan.edit_ajax');
            Route::put('/{id}/update_ajax', [KegiatanController::class, 'updateAjaxDosen'])->name('dosen.kegiatan.update_ajax');
            Route::get('/{id}/delete_ajax', [KegiatanController::class, 'deleteAjaxDosen'])->name('dosen.kegiatan.delete_ajax');
            Route::get('/{id}/export_word', [KegiatanController::class, 'exportWordDosen'])->name('dosen.kegiatan.export_word');
            Route::post('/{id}/upload_surat_tugas', [KegiatanController::class, 'uploadSuratTugas'])->name('dosen.kegiatan.upload_surat_tugas');
        });

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'dosen']);
            Route::post('/list', [StatistikController::class, 'list'])->name('dosen.statistik.list');
        });
    });

    Route::group(['prefix' => 'dosenPIC', 'middleware' => ['authorize:dosenPIC,dosen']], function () {
        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'dosenPIC'])->name('dosenPIC.kegiatan.index');
            Route::post('/list', [KegiatanController::class, 'listDosenPIC'])->name('dosenPIC.kegiatan.list');
            Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxDosenPIC'])->name('dosenPIC.kegiatan.show_ajax');
            Route::post('/ajax', [KegiatanController::class, 'storeDosenPIC'])->name('dosenPIC.storeAjax');
            Route::get('/{id}/edit_ajax', [KegiatanController::class, 'editAjaxDosenPIC'])->name('dosenPIC.kegiatan.edit_ajax');
            Route::put('/{id}/update_ajax', [KegiatanController::class, 'updateAjaxDosenPIC'])->name('dosenPIC.kegiatan.update_ajax');
            Route::get('/{id}/delete_ajax', [KegiatanController::class, 'deleteAjaxDosenPIC'])->name('dosenPIC.kegiatan.delete_ajax');
        });

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'dosenPIC']);
        });

        Route::prefix('agendaAnggota')->group(function () {
            Route::get('/', [KegiatanController::class, 'agendaAnggota'])->name('dosenPIC.agendaAnggota.index');
        });

        Route::prefix('agendaAnggota')->group(function () {
            Route::get('agendaAnggota/index', [KegiatanController::class, 'agendaAnggota'])->name('agendaAnggota.index');
            Route::get('agendaAnggota/edit/{id}', [KegiatanController::class, 'editAgendaAnggota'])->name('agendaAnggota.edit_ajax');
            Route::get('agendaAnggota/detail/{id}', [KegiatanController::class, 'detailAgendaAnggota'])->name('agendaAnggota.detail');
            Route::delete('agendaAnggota/delete/{id}', [KegiatanController::class, 'deleteAgendaAnggota'])->name('agendaAnggota.delete');
        });
    });

    Route::prefix('agendaAnggota')->group(function () {
        Route::get('/', [KegiatanController::class, 'agendaAnggota'])->name('agendaAnggota.index');
        Route::get('/edit/{id}', [KegiatanController::class, 'editAgendaAnggota'])->name('agendaAnggota.edit');
        Route::get('/detail/{id}', [KegiatanController::class, 'detailAgendaAnggota'])->name('agendaAnggota.detail');
        Route::delete('/delete/{id}', [KegiatanController::class, 'deleteAgendaAnggota'])->name('agendaAnggota.delete');
        Route::put('/update/{id}', [KegiatanController::class, 'updateAgendaAnggota'])->name('agendaAnggota.update');
    });
});


//Route dosenPIC
// Index
// Route::get('/dosenPIC/kegiatan',[KegiatanController::class, 'dosenPIC']);
// Route::get('/dosenPIC/statistik',[StatistikController::class, 'dosenPIC']);
// Route::get('dosenPIC/user', [UserController::class, 'dosenPIC']);
// Route::post('/dosenPIC/kegiatan/list', [KegiatanController::class, 'list'])->name('dosenpic.kegiatan.list');

//Route dosenAnggota
// Index
Route::get('/dosenAnggota/kegiatan', [KegiatanController::class, 'dosenAnggota']);
Route::get('/dosenAnggota/statistik', [StatistikController::class, 'dosenAnggota']);
