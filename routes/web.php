<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('user.edit');
    Route::patch('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\User\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/book-activity', [App\Http\Controllers\BookTransaction\BookActivityController::class, 'index'])->name('book.activity.index');
    Route::get('/book-activity/{transactionId}', [App\Http\Controllers\BookTransaction\BookActivityController::class, 'detail'])->name('book.activity.detail');
    Route::patch('/book-activity/{transactionId}/cancel', [App\Http\Controllers\BookTransaction\BookTransactionController::class, 'cancel'])->name('book.transaction.cancel');

    Route::get('/book-stock', [App\Http\Controllers\BookStock\BookStockController::class, 'index'])->name('book.stock.index');

    Route::get('/payment', [App\Http\Controllers\Transaction\PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{transactionId}', [App\Http\Controllers\Transaction\PaymentController::class, 'detail'])->name('payment.detail');

    // Route untuk Owner saja
    Route::middleware('role:1')->group(function () {
        Route::get('/invite', [App\Http\Controllers\Auth\InviteUserController::class, 'create'])->name('invite.create');
        Route::post('/invite', [App\Http\Controllers\Auth\InviteUserController::class, 'store'])->name('invite.store');

        Route::get('/users', [App\Http\Controllers\User\UserController::class, 'index'])->name('user.index');
        Route::put('/users/{userId}/update', [App\Http\Controllers\User\UserController::class, 'update'])->name('user.update');

        Route::get('/roles', [App\Http\Controllers\User\RoleController::class, 'index'])->name('user.role');

        Route::patch('/book-activity/{transactionId}/rejected', [App\Http\Controllers\BookTransaction\BookTransactionController::class, 'reject'])->name('book.transaction.reject');
        Route::patch('/book-activity/{transactionId}/approved', [App\Http\Controllers\BookTransaction\BookTransactionController::class, 'approve'])->name('book.transaction.approve.in');
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

        Route::get('/books', [App\Http\Controllers\Book\BookController::class, 'index'])->name('books.index');
        Route::get('/books/create', [App\Http\Controllers\Book\BookController::class, 'create'])->name('books.create');
        Route::post('/books/create', [App\Http\Controllers\Book\BookController::class, 'store'])->name('books.store');
        Route::get('/books/{bookId}/update', [App\Http\Controllers\Book\BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{bookId}/update', [App\Http\Controllers\Book\BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{bookId}/delete', [App\Http\Controllers\Book\BookController::class, 'destroy'])->name('books.destroy');

        Route::get('/semesters', [App\Http\Controllers\BookTransaction\SemesterController::class, 'index'])->name('semester.index');
        Route::delete('/semesters/{semesterId}/delete', [App\Http\Controllers\BookTransaction\SemesterController::class, 'destroy'])->name('semester.destroy');
        Route::put('/semesters/{semesterId}/update', [App\Http\Controllers\BookTransaction\SemesterController::class, 'update'])->name('semester.update');
        Route::post('/semesters', [App\Http\Controllers\BookTransaction\SemesterController::class, 'store'])->name('semester.store');

        Route::get('/transaction-type', [App\Http\Controllers\BookTransaction\TransactionTypeController::class, 'index'])->name('transaction.type.index');

        Route::get('/book-stock-in', [App\Http\Controllers\BookTransaction\BookTransactionController::class, 'createIn'])->name('book.stock.in.create');
        Route::post('/book-stock-in', [App\Http\Controllers\BookTransaction\BookTransactionController::class, 'storeIn'])->name('book.stock.in.store');

        Route::get('/omzet', [App\Http\Controllers\Transaction\TransactionController::class, 'omzet'])->name('transaction.omzet.index');
        Route::get('/revenue', [App\Http\Controllers\Transaction\TransactionController::class, 'revenue'])->name('transaction.revenue.index');
    });

    Route::middleware('role:1,3')->group(function () {
        Route::post('/book-stock/order', [App\Http\Controllers\BookStock\BookStockController::class, 'order'])->name('book.stock.order');
    });

    Route::middleware('role:2')->group(function () {
        Route::post('/payment/{transactionId}', [App\Http\Controllers\Transaction\PaymentController::class, 'store'])->name('payment.store');
        Route::put('/payment/{paymentId}', [App\Http\Controllers\Transaction\PaymentController::class, 'update'])->name('payment.update');
        Route::delete('/payment/{paymentId}', [App\Http\Controllers\Transaction\PaymentController::class, 'destroy'])->name('payment.destroy');
    });
});

require __DIR__ . '/auth.php';
