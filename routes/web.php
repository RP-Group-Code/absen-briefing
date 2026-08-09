<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BcfRegistrasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportPegawaiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexAbsenController;
use App\Http\Controllers\InputAbsenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" m®ddleware group. Make something great!
|
*/

Route::get(
    '/',
    fn() => Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login')
);


Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard/index', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/absen', [IndexAbsenController::class, 'index'])->name('absen.dashboard');
    Route::get('/dashboard/pegawai', [PegawaiController::class, 'index'])->name('pegawai.dashboard');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::get('/dashboard/pegawai-kanca', [PegawaiController::class, 'indexKanca'])->name('pegawai.kanca.index');
    Route::post('/dashboard/pegawai-kanca', [PegawaiController::class, 'storeKanca'])->name('pegawai.kanca.store');
    Route::put('/dashboard/pegawai-kanca/{kanca}', [PegawaiController::class, 'updateKanca'])->name('pegawai.kanca.update');
    Route::delete('/dashboard/pegawai-kanca/{kanca}', [PegawaiController::class, 'destroyKanca'])->name('pegawai.kanca.destroy');
    Route::get('/pegawai/import', [ImportPegawaiController::class, 'index'])->name('pegawai.import');
    Route::post('/pegawai/import', [ImportPegawaiController::class, 'store'])->name('pegawai.import.store');
    Route::get('/pegawai/import/template', [ImportPegawaiController::class, 'template'])->name('pegawai.import.template');
    Route::get('/delete-absen/{id}', [InputAbsenController::class, 'destroy'])->name('Delete-Absen');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AuthController::class, 'login']);
});

Route::get('/absen-briefing', [InputAbsenController::class, 'index'])->name('Input.Index');
Route::get('/absen-briefing-kanca', [InputAbsenController::class, 'indexKanca'])->name('Input-Index-Kanca');
Route::get('/absen-briefing-kanca/export', [InputAbsenController::class, 'exportKanca'])->name('absen.kanca.export');
Route::post('/absen-briefing-kanca/status', [InputAbsenController::class, 'saveKancaStatus'])->name('absen.kanca.status.save');

Route::get('/pegawai/by-unit/{uker_id}', [InputAbsenController::class, 'getPegawaiByUnit']);
Route::post('/submit/absen/briefing', [InputAbsenController::class, 'store'])->name('submit.absen');


Route::get('/bcf-registrasi', [BcfRegistrasiController::class, 'index'])->name('bcf.registrasi.index');
Route::post('/bcf-registrasi', [BcfRegistrasiController::class, 'store'])->name('bcf.registrasi.store');
Route::put('/bcf-registrasi/{id}', [BcfRegistrasiController::class, 'update'])->name('bcf.registrasi.update');
Route::delete('/bcf-registrasi/{id}', [BcfRegistrasiController::class, 'destroy'])->name('bcf.registrasi.destroy');
