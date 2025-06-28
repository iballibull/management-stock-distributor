<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\InviteUserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/invite', [InviteUserController::class, 'create'])->name('invite.create');
    Route::post('/invite', [InviteUserController::class, 'store'])->name('invite.store');
});

require __DIR__ . '/auth.php';
