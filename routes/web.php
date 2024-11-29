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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/api/kegiatan/events', [KegiatanController::class, 'getKegiatanEvents'])->name('api.kegiatan.events');



// Route::get('/', [DashboardController::class, 'index']);
Route::middleware('auth')->group(function () {
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

    Route::middleware('redirect.if.not.admin.or.pimpinan')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware('authorize:dosen')->group(function () {
        Route::get('/dashboard-dosen', [DashboardController::class, 'indexDosen'])->name('dashboard.dosen');
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
            Route::get('/import', [UserController::class, 'import'])->name('admin.user.import');
            Route::post('/import_ajax', [UserController::class, 'import_ajax'])->name('admin.user.import_ajax');
        });

        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'admin']);
            Route::post('/list', [KegiatanController::class, 'listAdmin'])->name('admin.kegiatan.list');
            Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxAdmin'])->name('admin.kegiatan.show_ajax');
            Route::get('/create_ajax', [KegiatanController::class, 'create_ajaxAdmin'])->name('admin.kegiatan.create_ajax');
            Route::post('/ajax', [KegiatanController::class, 'storeAdmin'])->name('admin.storeAjax');
            Route::get('/{id}/edit_ajax', [KegiatanController::class, 'editAjaxAdmin'])->name('admin.kegiatan.edit_ajax');
            Route::put('/{id}/update_ajax', [KegiatanController::class, 'updateAjaxAdmin'])->name('admin.kegiatan.update_ajax');
            Route::get('/export_pdf', [KegiatanController::class, 'exportPdf'])->name('admin.kegiatan.export_pdf');
            Route::get('/{id}/export_word', [KegiatanController::class, 'exportWord'])->name('admin.kegiatan.export_word');
            Route::post('/upload', [KegiatanController::class, 'uploadFile'])->name('kegiatan.upload');
            Route::delete('/{id}/delete_ajax', [KegiatanController::class, 'delete_ajaxAdmin'])->name('admin.kegiatan.confirm_ajax');
            Route::get('/{id}/delete_ajax', [KegiatanController::class, 'confirm_ajaxAdmin'])->name('admin.kegiatan.confirm_ajax');
            Route::delete('/{id}/confirm_ajax', [KegiatanController::class, 'delete_ajaxAdmin'])->name('admin.kegiatan.confirm_ajax');
            Route::get('/export_excel', [KegiatanController::class, 'exportExcel'])->name('admin.kegiatan.export_excel');
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
            Route::get('/export_pdf', [StatistikController::class, 'exportPdf'])->name('admin.statistik.export_pdf');
            Route::get('/export_excel', [StatistikController::class, 'exportExcel'])->name('admin.statistik.export_excel');
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
            Route::get('/export_pdf', [UserController::class, 'exportPdf'])->name('pimpinan.user.export_pdf');
        });

        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'pimpinan']);
            Route::post('/list', [KegiatanController::class, 'listPimpinan'])->name('pimpinan.kegiatan.list');
            Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajaxPimpinan'])->name('pimpinan.kegiatan.show_ajax');
            Route::get('/{id}/export_word', [KegiatanController::class, 'exportWord'])->name('pimpinan.kegiatan.export_word');
            Route::get('/export_pdf', [KegiatanController::class, 'exportPdf'])->name('pimpinan.kegiatan.export_pdf');
            Route::get('/export_excel', [KegiatanController::class, 'exportExcel'])->name('pimpinan.kegiatan.export_excel');
        });

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'pimpinan']);
            Route::post('/list', [StatistikController::class, 'list'])->name('pimpinan.statistik.list');
            Route::get('/export_pdf', [StatistikController::class, 'exportPdf'])->name('pimpinan.statistik.export_pdf');
            Route::get('/export_excel', [StatistikController::class, 'exportExcel'])->name('pimpinan.statistik.export_excel');
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
            Route::get('/{id}/delete_ajax', [KegiatanController::class, 'confirmAjaxDosen'])->name('dosen.kegiatan.confirm_ajax');
            Route::delete('/{id}/delete_ajax', [KegiatanController::class, 'deleteAjaxDosen'])->name('dosen.kegiatan.delete_ajax');
            Route::get('/{id}/export_word', [KegiatanController::class, 'exportWordDosen'])->name('dosen.kegiatan.export_word');
            Route::post('/{id}/upload_surat_tugas', [KegiatanController::class, 'uploadSuratTugas'])->name('dosen.kegiatan.upload_surat_tugas');
            Route::get('/data', [KegiatanController::class, 'data'])->name('dosen.kegiatan.data');
            Route::get('/export_pdf', [KegiatanController::class, 'exportPdf_dosen'])->name('dosen.kegiatan.export_pdf');
            Route::get('/export_excel', [KegiatanController::class, 'exportExcel_dosen'])->name('dosen.kegiatan.export_excel');

            //jti
            Route::get('/jti', [KegiatanController::class, 'KegiatanJTI']);
            Route::post('/jti/list', [KegiatanController::class, 'listDosenJTI'])->name('dosen.kegiatan.jti.list');
            Route::get('/jti/{id}/show_ajax', [KegiatanController::class, 'show_ajaxDosenJTI'])->name('dosen.kegiatan.jti.show_ajax');

            //non-jti
            Route::get('/nonjti', [KegiatanController::class, 'KegiatanNonJTI']);
            Route::post('/nonjti/list', [KegiatanController::class, 'listDosenNonJTI'])->name('dosen.kegiatan.nonjti.list');
            Route::get('/nonjti/{id}/show_ajax', [KegiatanController::class, 'show_ajaxDosenNonJTI'])->name('dosen.kegiatan.nonjti.show_ajax');

        });

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'dosen']);
            Route::post('/list', [StatistikController::class, 'list'])->name('dosen.statistik.list');
            Route::get('/export_pdf', [StatistikController::class, 'exportPdf'])->name('dosen.statistik.export_pdf');
            Route::get('/export_excel', [StatistikController::class, 'exportExcel'])->name('dosen.statistik.export_excel');
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
            Route::get('/export_pdf', [KegiatanController::class, 'exportPdf_pic'])->name('dosenPIC.kegiatan.export_pdf');
            Route::get('/export_excel', [KegiatanController::class, 'exportExcel_pic'])->name('dosenPIC.kegiatan.export_excel');

            Route::get('/jti', [KegiatanController::class, 'listDosenJti'])->name('dosen.kegiatan.jti.index');
        });

        Route::prefix('statistik')->group(function () {
            Route::get('/', [StatistikController::class, 'dosenPIC']);
        });

        Route::prefix('agendaAnggota')->group(function () {
            Route::get('/', [KegiatanController::class, 'agendaAnggota'])->name('agendaAnggota.index');
            Route::get('/edit/{id}', [KegiatanController::class, 'editAgendaAnggota'])->name('agendaAnggota.edit');
            Route::get('/detail/{id}', [KegiatanController::class, 'detailAgendaAnggota'])->name('agendaAnggota.detail');
            Route::delete('/delete/{id}', [KegiatanController::class, 'deleteAgendaAnggota'])->name('agendaAnggota.delete');
            Route::put('/update/{id}', [KegiatanController::class, 'updateAgendaAnggota'])->name('agendaAnggota.update');
        });

        Route::prefix('progresKegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'progresKegiatan'])->name('progresKegiatan.index');
            Route::get('/edit/{id}', [KegiatanController::class, 'editProgresKegiatan'])->name('progresKegiatan.edit');
            Route::patch('/update/{id}', [KegiatanController::class, 'updateProgresKegiatan'])->name('progresKegiatan.update');
            Route::get('/detail/{id}', [KegiatanController::class, 'detailProgresKegiatan'])->name('progresKegiatan.detail');
            Route::post('/list', [KegiatanController::class, 'listProgresKegiatan'])->name('dosenPIC.progresKegiatan.list');
        });
    });

    Route::group(['prefix' => 'dosenAnggota', 'middleware' => ['authorize:dosenAnggota,dosen']], function () {
        Route::prefix('kegiatan')->group(function () {
        Route::get('/', [KegiatanController::class, 'dosenAnggota'])->name('dosenAnggota.kegiatan.dosenA');
        Route::post('/list', [KegiatanController::class, 'listDosenAnggota'])->name('dosenAnggota.kegiatan.list');
        Route::post('/ajax', [KegiatanController::class, 'storeDosenAnggota'])->name('dosenAnggota.storeAjax');
        Route::get('/dataDosenA', [KegiatanController::class, 'dataDosenA'])->name('dosenAnggota.kegiatan.dataDosenA');
        Route::get('/export_pdf', [KegiatanController::class, 'exportPdf_dosenAnggota'])->name('dosenAnggota.kegiatan.export_pdf');
        Route::get('/export_excel', [KegiatanController::class, 'exportExcel_dosenAnggota'])->name('dosenAnggota.kegiatan.export_excel');
        });
});
