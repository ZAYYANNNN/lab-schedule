<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return 'halaman profile';
    })->name('profile.edit');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// SUPERADMIN AREA
Route::middleware(['auth', 'role:superadmin'])->group(function () {

    Route::prefix('superadmin')
        ->name('superadmin.')
        ->middleware(['auth', 'role:superadmin'])
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', fn() => view('superadmin.dashboard'))
                ->name('dashboard');

            // LAB CRUD
            Route::resource('labs', LabController::class);

            // ASSETS (SEMUA LAB)
            Route::get('/assets', [LabAssetController::class, 'allAssets'])
                ->name('assets.index');
            
            Route::get('/pendaftaran', [LabAssetController::class, 'allPendftaran'])
                ->name('pendaftaran.index');
        });


    /**
     * LAB MANAGEMENT (Superadmin bisa kelola semua lab)
     */
    Route::prefix('superadmin')
        ->name('superadmin.')
        ->middleware(['auth', 'role:superadmin'])
        ->group(function () {

            Route::resource('labs', LabController::class);
        });


    /**
     * LAB ASSET
     */
    Route::get('/superadmin/jadwal', [ScheduleController::class, 'allLabs'])
        ->name('superadmin.jadwal.index');


    Route::post('/superadmin/labs/{lab}/assets', [LabAssetController::class, 'store'])
        ->name('superadmin.labs.assets.store');

    Route::put('/superadmin/labs/{lab}/assets/{asset}', [LabAssetController::class, 'update'])
        ->name('superadmin.labs.assets.update');

    Route::delete('/superadmin/labs/{lab}/assets/{asset}', [LabAssetController::class, 'destroy'])
        ->name('superadmin.labs.assets.destroy');

    /**
     * LAB SCHEDULE
     */
    Route::get('/superadmin/labs/{lab}/schedule', [ScheduleController::class, 'index'])
        ->name('superadmin.labs.schedule.index');

    Route::post('/superadmin/labs/{lab}/schedule', [ScheduleController::class, 'store'])
        ->name('superadmin.labs.schedule.store');

    Route::put('/superadmin/labs/{lab}/schedule/{schedule}', [ScheduleController::class, 'update'])
        ->name('superadmin.labs.schedule.update');

    Route::delete('/superadmin/labs/{lab}/schedule/{schedule}', [ScheduleController::class, 'destroy'])
        ->name('superadmin.labs.schedule.destroy');
});


// ADMIN AREA
Route::middleware(['auth', 'role:admin', 'labaccess'])->group(function () {

    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))
        ->name('admin.dashboard');

    // DAFTAR LAB (resource)
    Route::resource('labs', LabController::class);

    // ASET LAB
    Route::get('/labs/{lab}/assets', [LabAssetController::class, 'index'])
        ->name('labs.assets.index');
    Route::post('/labs/{lab}/assets', [LabAssetController::class, 'store'])
        ->name('labs.assets.store');
    Route::put('/labs/{lab}/assets/{asset}', [LabAssetController::class, 'update'])
        ->name('labs.assets.update');
    Route::delete('/labs/{lab}/assets/{asset}', [LabAssetController::class, 'destroy'])
        ->name('labs.assets.destroy');

    // JADWAL LAB
    Route::get('/labs/{lab}/schedule', [ScheduleController::class, 'index'])
        ->name('labs.schedule.index');
    Route::post('/labs/{lab}/schedule', [ScheduleController::class, 'store'])
        ->name('labs.schedule.store');
    Route::put('/labs/{lab}/schedule/{schedule}', [ScheduleController::class, 'update'])
        ->name('labs.schedule.update');
    Route::delete('/labs/{lab}/schedule/{schedule}', [ScheduleController::class, 'destroy'])
        ->name('labs.schedule.destroy');
});
