<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\CekPelaporanController;
use App\Http\Controllers\PelaporanMasukController;
use App\Http\Controllers\TambahPelaporanController;
use App\Http\Controllers\PelaporanSelesaiController;
use App\Http\Controllers\LaporanInventarisController;
use App\Http\Controllers\LaporanPerbaikanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Auth\MagicLoginController;

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



Auth::routes([
    'register' => false,
    'reset'    => true,
    'verify'   => false,
]);

Route::middleware('guest')->group(function () {
    Route::get('/login/magic/{user}', [MagicLoginController::class, 'authenticate'])
        ->middleware('signed')
        ->name('login.magic');
});
Route::middleware('auth')->group(function () {
    // Rute yang dapat diakses oleh Manager dan Admin
    Route::group(['middleware'  => 'checkrole:manager,admin'], function () {
        Route::get('/', [HomeController::class, 'index']);
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        Route::get('/asets/cetak-semua-qr', [AsetController::class, 'cetakSemuaQr']);
        Route::get('/asets/{id}/cetak-qr', [AsetController::class, 'cetakQr']);
        Route::resource('/asets', AsetController::class);
        Route::resource('/kategori', KategoriController::class);
        Route::resource('/lokasi', LokasiController::class);

        Route::get('/pelaporan-selesai/cetak-laporan/{id}', [PelaporanSelesaiController::class, 'cetakLaporan']);
        Route::get('/pelaporan-selesai', [PelaporanSelesaiController::class, 'index']);

        Route::get('/profil', [ChangePasswordController::class, 'edit'])->name('profile');
        Route::put('/profil', [ChangePasswordController::class, 'update'])->name('profile.update');
        Route::get('/ubah-password', [ChangePasswordController::class, 'edit'])->name('password.change');
        Route::put('/ubah-password', [ChangePasswordController::class, 'update'])->name('password.change.update');

    });

    // Rute khusus Manager (sebelumnya admin / role ID 1)
    Route::group(['middleware'  => 'checkrole:manager'], function () {
        Route::get('/pelaporan-masuk', [PelaporanMasukController::class, 'index']);
        Route::get('/pelaporan-masuk/detail/{id}', [PelaporanMasukController::class, 'detail']);
        Route::put('/pelaporan-masuk/detail/{pelaporan:id}/perbaiki', [PelaporanMasukController::class, 'perbaiki']);
        Route::put('/pelaporan-masuk/detail/{pelaporan:id}/selesai', [PelaporanMasukController::class, 'selesai']);

        Route::get('/laporan-inventaris', [LaporanInventarisController::class, 'index']);
        Route::get('/laporan-inventaris/laporan', [LaporanInventarisController::class, 'cetakLaporan']);

        Route::get('/laporan-perbaikan', [LaporanPerbaikanController::class, 'index']);
        Route::get('/laporan-perbaikan/laporan', [LaporanPerbaikanController::class, 'cetakLaporan']);
        
        Route::resource('user', UserController::class)->except(['show']);
    });

    // Rute khusus Admin (sebelumnya user / role ID 2)
    Route::group(['middleware'  => 'checkrole:admin'], function () {
        Route::get('/tambah-pelaporan', [TambahPelaporanController::class, 'index']);
        Route::get('/get-data-aset', [TambahPelaporanController::class, 'getDataAset']);
        Route::post('/tambah-pelaporan', [TambahPelaporanController::class, 'store']);

        Route::get('/cek-pelaporan', [CekPelaporanController::class, 'index']);
        Route::get('/cek-pelaporan/detail/{id}', [CekPelaporanController::class, 'detail']);
        Route::post('/cek-pelaporan/detail/{id}', [CekPelaporanController::class, 'store']);
    });
});
