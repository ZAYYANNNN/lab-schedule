<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LabAssetController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

// Dashboard untuk user yang SUDAH login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return 'halaman profile';
    })->name('profile.edit');
});


// ==============================
// SUPERADMIN AREA
// ==============================
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('superadmin.dashboard'))
            ->name('dashboard');

        // LABS (superadmin version - prefix berbeda)
        Route::resource('labs', LabController::class);

        // ASSETS (global - tidak nested)
        Route::get('/assets', [LabAssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [LabAssetController::class, 'store'])->name('assets.store');
        Route::put('/assets/{asset}', [LabAssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{asset}', [LabAssetController::class, 'destroy'])->name('assets.destroy');

        // JADWAL (global - tidak nested)
        Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal', [ScheduleController::class, 'store'])->name('jadwal.store');
        Route::put('/jadwal/{schedule}', [ScheduleController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{schedule}', [ScheduleController::class, 'destroy'])->name('jadwal.destroy');
    });



// ==============================
// ADMIN AREA
// ==============================
Route::middleware(['auth', 'role:admin', 'labaccess'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('admin.dashboard'))
            ->name('dashboard');

        // DAFTAR LAB ADMIN
        Route::resource('labs', LabController::class);

        // ASSETS PER-LAB (nested)
        Route::get('/labs/{lab}/assets', [LabAssetController::class, 'index'])->name('labs.assets.index');
        Route::post('/labs/{lab}/assets', [LabAssetController::class, 'store'])->name('labs.assets.store');
        Route::put('/labs/{lab}/assets/{asset}', [LabAssetController::class, 'update'])->name('labs.assets.update');
        Route::delete('/labs/{lab}/assets/{asset}', [LabAssetController::class, 'destroy'])->name('labs.assets.destroy');

        // JADWAL PER-LAB (nested)
        Route::get('/labs/{lab}/schedule', [ScheduleController::class, 'index'])->name('labs.schedule.index');
        Route::post('/labs/{lab}/schedule', [ScheduleController::class, 'store'])->name('labs.schedule.store');
        Route::put('/labs/{lab}/schedule/{schedule}', [ScheduleController::class, 'update'])->name('labs.schedule.update');
        Route::delete('/labs/{lab}/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('labs.schedule.destroy');
    });
