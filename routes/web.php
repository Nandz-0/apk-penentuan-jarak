<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaskesController;
use App\Http\Controllers\Admin\JalurController;
use App\Http\Controllers\Admin\KategoriFaskesController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\TitikController;
use App\Http\Controllers\DashboardController as UserDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman User (Public)
|--------------------------------------------------------------------------
| Beranda, daftar & detail faskes, peta, serta pencarian rekomendasi faskes.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/faskes', [HomeController::class, 'peta'])->name('faskes');
Route::get('/faskes/{faskes}', [HomeController::class, 'detail'])->name('faskes.detail')->whereNumber('faskes');
Route::get('/peta', [HomeController::class, 'peta'])->name('peta');

Route::get('/search', [HomeController::class, 'index'])->name('search');
Route::post('/search', [RekomendasiController::class, 'cari'])->name('search.cari');

/*
|--------------------------------------------------------------------------
| Halaman User Terautentikasi
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Halaman Admin
|--------------------------------------------------------------------------
| Dilindungi middleware 'auth' (wajib login) dan 'admin' (role admin).
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map', [MapController::class, 'index'])->name('map');

    Route::resource('faskes', FaskesController::class, ['parameters' => ['faskes' => 'faskes']]);
    Route::resource('kategori-faskes', KategoriFaskesController::class, ['parameters' => ['kategori-faskes' => 'kategoriFaskes']]);
    Route::resource('nodes', TitikController::class)->except('show');
    Route::resource('edges', JalurController::class, ['parameters' => ['edges' => 'jalur']])->except('show');
});

require __DIR__.'/auth.php';