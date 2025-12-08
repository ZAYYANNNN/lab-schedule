<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Routes untuk semua user yang login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users - Superadmin only (proteksi di controller)
    Route::resource('users', UserController::class);
});

// Routes yang MEMERLUKAN pengecekan Prodi (Admin dibatasi per Prodi)
Route::middleware(['auth', 'labaccess'])->group(function () {
    // Labs
    Route::resource('labs', LabController::class);

    // Assets
    Route::resource('assets', AssetController::class);

    // Schedules
    Route::resource('schedules', ScheduleController::class);

    // Borrowings
    Route::resource('borrowings', BorrowingController::class);
});
