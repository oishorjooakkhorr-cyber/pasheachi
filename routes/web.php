<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
| Role route groups. Add each feature's routes inside the group for the
| role that uses it. The 'role' middleware returns 403 to anyone else.
*/

// Client pages: F1, F2, F4, F6, F7, F8, F9, F10, F17
Route::middleware(['auth', 'role:client'])->group(function () {
    //
});

// Runner pages: F11 (after sign-up), F12, F13, F14, F15, F18
Route::middleware(['auth', 'role:runner'])->prefix('runner')->name('runner.')->group(function () {
    //
});

// Admin pages: F3 (rates), F10 (disputes), F19, F20
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    //
});

require __DIR__.'/auth.php';
