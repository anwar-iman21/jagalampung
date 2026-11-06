<?php

use App\Http\Controllers\Admin\EmergencyAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\EmergencyController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MapController;
use App\Http\Controllers\Public\ReportController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lapor', [ReportController::class, 'create'])->name('lapor');
Route::post('/lapor', [ReportController::class, 'store'])->name('lapor.store');
Route::get('/lapor/selesai/{code}', [ReportController::class, 'done'])->name('lapor.done');
Route::get('/lacak', [ReportController::class, 'trackForm'])->name('lacak');
Route::post('/lacak', [ReportController::class, 'track'])->middleware('throttle:10,1')->name('lacak.cek');
Route::get('/peta', [MapController::class, 'index'])->name('peta');
Route::get('/peta/data', [MapController::class, 'data'])->name('peta.data');

Route::get('/darurat', [EmergencyController::class, 'index'])->name('darurat');

Route::get('/masuk', [LoginController::class, 'show'])->name('login');
Route::post('/masuk', [LoginController::class, 'login'])->middleware('throttle:6,1')->name('login.post');
Route::post('/keluar', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', EnsureRole::class.':admin,super_admin'])->prefix('admin')->group(function () {
    Route::get('/laporan', [ReportAdminController::class, 'index'])->name('admin.reports');
    Route::get('/laporan/{report}', [ReportAdminController::class, 'show'])->name('admin.report');
    Route::post('/laporan/{report}', [ReportAdminController::class, 'update'])->name('admin.report.update');
    Route::get('/statistik', [StatsController::class, 'index'])->name('admin.stats');
    Route::get('/statistik/csv', [StatsController::class, 'csv'])->name('admin.stats.csv');
    Route::get('/darurat', [EmergencyAdminController::class, 'index'])->name('admin.darurat');
    Route::post('/darurat/kontak', [EmergencyAdminController::class, 'contact'])->name('admin.darurat.contact');
    Route::post('/darurat/evakuasi', [EmergencyAdminController::class, 'evac'])->name('admin.darurat.evac');
    Route::post('/darurat/peringatan', [EmergencyAdminController::class, 'alert'])->name('admin.darurat.alert');
});

Route::middleware(['auth', EnsureRole::class.':super_admin'])->prefix('admin')->group(function () {
    Route::get('/pengguna', [UserAdminController::class, 'index'])->name('admin.users');
    Route::post('/pengguna', [UserAdminController::class, 'store'])->name('admin.users.store');
    Route::post('/pengguna/{user}/toggle', [UserAdminController::class, 'toggle'])->name('admin.users.toggle');
});
