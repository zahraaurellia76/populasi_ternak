<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PopulasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\JenisTernakController;

use App\Http\Controllers\Admin\PrediksiController;


// Halaman Publik
Route::get('/', [PublicController::class, 'index'])->name('public.index');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route khusus yang membutuhkan Login
Route::middleware(['auth'])->group(function () {
    
    // ==========================================
    // ADMIN KABUPATEN ROUTES
    // ==========================================
    Route::get('/admin-kabupaten/dashboard', [AdminController::class, 'dashboardKabupaten'])->name('admin.kabupaten.dashboard');
    Route::get('/admin-kabupaten/rekapitulasi', [AdminController::class, 'rekapitulasi'])->name('admin.kabupaten.rekapitulasi');
    Route::get('/admin-kabupaten/prediksi', [AdminController::class, 'prediksiKabupaten'])->name('admin.kabupaten.prediksi');

    // CRUD Data Ternak (Admin Kabupaten)
    Route::get('/admin-kabupaten/data-ternak', [AdminController::class, 'dataTernak'])->name('admin.kabupaten.data_ternak');
    Route::post('/admin-kabupaten/data-ternak', [AdminController::class, 'storeDataTernak'])->name('admin.kabupaten.data_ternak.store');
    Route::put('/admin-kabupaten/data-ternak/{id}', [AdminController::class, 'updateDataTernak'])->name('admin.kabupaten.data_ternak.update');
    Route::delete('/admin-kabupaten/data-ternak/{id}', [AdminController::class, 'destroyDataTernak'])->name('admin.kabupaten.data_ternak.destroy');

    // Kelola User
    Route::get('/users', [UserController::class, 'index'])->name('admin.kabupaten.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.kabupaten.users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.kabupaten.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.kabupaten.users.destroy');
    
    // Kelola Kecamatan (Admin Kabupaten)
    Route::get('/kecamatan', [KecamatanController::class, 'index'])->name('admin.kabupaten.kecamatan.index');
    Route::post('/kecamatan', [KecamatanController::class, 'store'])->name('admin.kabupaten.kecamatan.store');
    Route::put('/kecamatan/{id}', [KecamatanController::class, 'update'])->name('admin.kabupaten.kecamatan.update');
    Route::delete('/kecamatan/{id}', [KecamatanController::class, 'destroy'])->name('admin.kabupaten.kecamatan.destroy');

    // Kelola Jenis Ternak
    Route::get('/jenis-ternak', [JenisTernakController::class, 'index'])->name('admin.kabupaten.jenis_ternak.index');
    Route::post('/jenis-ternak', [JenisTernakController::class, 'store'])->name('admin.kabupaten.jenis_ternak.store');
    Route::put('/jenis-ternak/{id}', [JenisTernakController::class, 'update'])->name('admin.kabupaten.jenis_ternak.update');
    Route::delete('/jenis-ternak/{id}', [JenisTernakController::class, 'destroy'])->name('admin.kabupaten.jenis_ternak.destroy');

    // Export Rekapitulasi
    Route::get('/admin-kabupaten/rekapitulasi/pdf', [AdminController::class, 'cetakRekapPdf'])->name('admin.kabupaten.rekapitulasi.pdf');
    Route::get('/admin-kabupaten/rekapitulasi/excel', [AdminController::class, 'cetakRekapExcel'])->name('admin.kabupaten.rekapitulasi.excel');

    // ==========================================
    // ADMIN KECAMATAN ROUTES
    // ==========================================
    Route::get('/admin-kecamatan/dashboard', [AdminController::class, 'dashboardKecamatan'])->name('admin.kecamatan.dashboard');
    Route::get('/admin-kecamatan/prediksi', [KecamatanController::class, 'prediksi'])->name('admin.kecamatan.prediksi');

    // Kelola Populasi Ternak (Admin Kecamatan)
    Route::get('/admin-kecamatan/populasi', [KecamatanController::class, 'indexPopulasi'])->name('admin.kecamatan.populasi');
    Route::post('/admin-kecamatan/populasi', [KecamatanController::class, 'storePopulasi'])->name('admin.kecamatan.populasi.store');
    Route::put('/admin-kecamatan/populasi/{id}', [KecamatanController::class, 'updatePopulasi'])->name('admin.kecamatan.populasi.update');
    Route::delete('/admin-kecamatan/populasi/{id}', [KecamatanController::class, 'destroyPopulasi'])->name('admin.kecamatan.populasi.destroy');

    // Rekapitulasi Admin Kecamatan
    Route::get('/admin-kecamatan/rekapitulasi', [KecamatanController::class, 'rekapitulasiKecamatan'])->name('admin.kecamatan.rekapitulasi');

    // Route Export Rekapitulasi Admin Kecamatan
    Route::get('/admin-kecamatan/rekapitulasi/pdf', [KecamatanController::class, 'exportPdf'])->name('admin.kecamatan.rekapitulasi.pdf');
    Route::get('/admin-kecamatan/rekapitulasi/excel', [KecamatanController::class, 'exportExcel'])->name('admin.kecamatan.rekapitulasi.excel'); 

    Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi');
    });
    
});