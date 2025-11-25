<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Logika pengalihan berdasarkan peran (Role)
    if (auth()->check()) {
        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
    }
    // Jika tidak ada peran spesifik, kembalikan ke dashboard default
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin
Route::middleware(['auth', 'role:admin']) // Pastikan RoleMiddleware sudah dikonfigurasi
    ->prefix('admin') // Semua route dimulai dengan '/admin'
    ->name('admin.') // Semua nama route dimulai dengan 'admin.'
    ->group(function () {
        
        // Dashboard Admin (Controller yang Anda berikan)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
