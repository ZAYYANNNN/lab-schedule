<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LabAssetController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

// Dashboard untuk user yang SUDAH login
Route::get('/dashboard', function () {
    return view('superadmin.dashboard'); 
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::get('/check', function () {
    return auth()->check() ? 'LOGGED IN' : 'NOT LOGGED IN';
});



// ==============================
// AUTH AREA
// ==============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==============================
// PROFILE (AUTH ONLY)
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return 'halaman profile';
    })->name('profile.edit');
});


/// ==============================
// SUPERADMIN AREA
// ==============================
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('superadmin.dashboard'))
            ->name('dashboard');

        // LABS
        Route::resource('labs', LabController::class);

        // 🔥 1. Semua aset (TANPA labId)
        Route::get('/assets', [LabAssetController::class, 'allAssets'])
            ->name('assets.index');

        // 🔥 2. Aset per LAB (DENGAN labId)
        Route::get('/labs/{labId}/assets', [LabAssetController::class, 'index'])
            ->name('labs.assets.index');

        Route::post('/labs/{labId}/assets', [LabAssetController::class, 'store'])
            ->name('labs.assets.store');

        Route::put('/labs/{labId}/assets/{assetId}', [LabAssetController::class, 'update'])
            ->name('labs.assets.update');

        Route::delete('/labs/{labId}/assets/{assetId}', [LabAssetController::class, 'destroy'])
            ->name('labs.assets.destroy');

        // Jadwal
        Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal', [ScheduleController::class, 'store'])->name('jadwal.store');
        Route::put('/jadwal/{schedule}', [ScheduleController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{schedule}', [ScheduleController::class, 'destroy'])->name('jadwal.destroy');

        // User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
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
