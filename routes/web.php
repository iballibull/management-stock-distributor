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

    // Route untuk Owner dan Admin
    Route::middleware('role:1,2')->group(function () {
        Route::get('/categories', [App\Http\Controllers\Book\CategoryController::class, 'index'])->name('category.index');
        Route::post('/categories', [App\Http\Controllers\Book\CategoryController::class, 'store'])->name('category.store');
        Route::put('/categories/{categoryId}/update', [App\Http\Controllers\Book\CategoryController::class, 'update'])->name('category.update');
        Route::delete('/categories/{categoryId}/delete', [App\Http\Controllers\Book\CategoryController::class, 'destroy'])->name('category.destroy');

        Route::get('/curriculums', [App\Http\Controllers\Book\CurriculumController::class, 'index'])->name('curriculum.index');
        Route::post('/curriculums', [App\Http\Controllers\Book\CurriculumController::class, 'store'])->name('curriculum.store');
        Route::put('/curriculums/{curriculumId}/update', [App\Http\Controllers\Book\CurriculumController::class, 'update'])->name('curriculum.update');
        Route::delete('/curriculums/{curriculumId}/delete', [App\Http\Controllers\Book\CurriculumController::class, 'destroy'])->name('curriculum.destroy');

        Route::get('/education-levels', [App\Http\Controllers\Book\EducationLevelController::class, 'index'])->name('education.level.index');
        Route::post('/education-levels', [App\Http\Controllers\Book\EducationLevelController::class, 'store'])->name('education.level.store');
        Route::put('/education-levels/{educationLevelId}/update', [App\Http\Controllers\Book\EducationLevelController::class, 'update'])->name('education.level.update');
        Route::delete('/education-levels/{educationLevelId}/delete', [App\Http\Controllers\Book\EducationLevelController::class, 'destroy'])->name('education.level.destroy');
    });
});

require __DIR__ . '/auth.php';
