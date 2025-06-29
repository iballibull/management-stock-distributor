<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('user.edit');
    Route::patch('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\User\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk Owner saja
    Route::middleware('role:1')->group(function () {
        Route::get('/invite', [App\Http\Controllers\Auth\InviteUserController::class, 'create'])->name('invite.create');
        Route::post('/invite', [App\Http\Controllers\Auth\InviteUserController::class, 'store'])->name('invite.store');

        Route::get('/users', [App\Http\Controllers\User\UserController::class, 'index'])->name('user.index');
        Route::put('/users/{userId}/update', [App\Http\Controllers\User\UserController::class, 'update'])->name('user.update');

        Route::get('/roles', [App\Http\Controllers\User\RoleController::class, 'index'])->name('user.role');
    });
});

require __DIR__ . '/auth.php';
