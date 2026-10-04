<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Welcome / Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog (Accessible to both guests and authenticated users)
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/katalog/{book}/baca', [CatalogController::class, 'read'])->name('catalog.read');

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes (Member and Admin)
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Member Borrowings
    Route::get('/peminjaman-saya', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/katalog/{book}/pinjam', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::delete('/peminjaman-saya/{borrowing}', [BorrowingController::class, 'cancel'])->name('borrowings.cancel');

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Books Management
        Route::get('/buku', [AdminBookController::class, 'index'])->name('books.index');
        Route::get('/buku/tambah', [AdminBookController::class, 'create'])->name('books.create');
        Route::post('/buku', [AdminBookController::class, 'store'])->name('books.store');
        Route::get('/buku/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
        Route::put('/buku/{book}', [AdminBookController::class, 'update'])->name('books.update');
        Route::delete('/buku/{book}', [AdminBookController::class, 'destroy'])->name('books.destroy');

        // Categories Management
        Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/kategori/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // Borrowings Management
        Route::get('/peminjaman', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
        Route::post('/peminjaman/{borrowing}/setujui', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::post('/peminjaman/{borrowing}/tolak', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::post('/peminjaman/{borrowing}/kembali', [AdminBorrowingController::class, 'returnConfirm'])->name('borrowings.returnConfirm');

        // Members Management
        Route::get('/anggota', [AdminMemberController::class, 'index'])->name('members.index');
        Route::get('/anggota/{user}', [AdminMemberController::class, 'show'])->name('members.show');
    });
});
