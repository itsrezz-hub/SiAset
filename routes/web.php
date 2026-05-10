<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SI ASET (Sistem Informasi Aset Sekolah)
|--------------------------------------------------------------------------
*/

// --- 1. AKSES PUBLIK (Visitor) ---
// Menampilkan halaman welcome.blade.php melalui fungsi publicIndex
Route::get('/', [AssetController::class, 'publicIndex'])->name('welcome');


// --- 2. AKSES TERPROTEKSI (Login Required) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // A. Akun Profile (User & Admin)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // B. Akses Manajemen Aset (Admin Gudang & Superadmin)
    Route::middleware(['can:admin-access'])->group(function () {
        
        /**
         * DASHBOARD UTAMA ADMIN
         */
        Route::get('/dashboard', [AssetController::class, 'index'])->name('dashboard');

        /**
         * MANAJEMEN DATA ASET (CRUD)
         */
        // Create (Tambah)
        Route::get('/aset/tambah', [AssetController::class, 'create'])->name('aset.create');
        Route::post('/aset/simpan', [AssetController::class, 'store'])->name('aset.store');

        // Edit & Update
        Route::get('/aset/edit/{id}', [AssetController::class, 'edit'])->name('aset.edit');
        Route::put('/aset/update/{id}', [AssetController::class, 'update'])->name('aset.update');

        // Delete (Hapus)
        Route::delete('/aset/hapus/{id}', [AssetController::class, 'destroy'])->name('aset.destroy');

        /** 
         * FITUR EKSPOR EXCEL & TRANSAKSI
         */
        Route::get('/aset/export-excel', [AssetController::class, 'exportExcel'])->name('aset.exportExcel');
        Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');


        /**
         * --- C. KHUSUS SUPERADMIN (Panel Kendali) ---
         */
        Route::prefix('admin-panel')->group(function () {
            // Manajemen Pengguna
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            
            // Monitoring Aset secara menyeluruh
            Route::get('/aset/monitoring', [AssetController::class, 'monitoring'])->name('aset.monitoring');
        });
    });
});

require __DIR__.'/auth.php';