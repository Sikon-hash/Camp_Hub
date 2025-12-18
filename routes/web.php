<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// --- PUBLIC ROUTES (Bisa diakses siapa saja) ---
Route::get('/', [HomeController::class, 'home']);
Route::get('product_details/{id}', [HomeController::class, 'product_details']);

// --- AUTHENTICATED USER ROUTES (Harus Login & Verified) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard User
    Route::get('/dashboard', [HomeController::class, 'login_home'])->name('dashboard');

    // Cart System (Menggunakan HomeController)
    Route::controller(HomeController::class)->group(function () {
        Route::get('add_cart/{id}', 'add_cart');
        Route::get('mycart', 'mycart');
        Route::get('delete_cart/{id}', 'delete_cart');
        Route::post('confirm_order', 'confirm_order');
    });

    // Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

// --- ADMIN ROUTES (ZONA KHUSUS INVESTIGATOR & ADMIN) ---
// Middleware: Harus Login (auth) DAN bertipe Admin (admin)
Route::middleware(['auth', 'admin'])->group(function () {

    // 1. Admin Dashboard (Menggunakan HomeController sesuai kode asli Anda)
    Route::get('admin/dashboard', [HomeController::class, 'index']);

    // 2. Semua Logika Admin (Menggunakan AdminController)
    Route::controller(AdminController::class)->group(function () {
        
        // === BLOCKCHAIN & SECURITY DASHBOARD (RUTE BARU) ===
        Route::get('/system_health', 'system_health')->name('system.health');

        // === CATEGORY MANAGEMENT ===
        Route::get('view_category', 'view_category');
        Route::post('add_category', 'add_category');
        Route::get('delete_category/{id}', 'delete_category');
        Route::get('edit_category/{id}', 'edit_category');
        Route::post('update_category/{id}', 'update_category');

        // === PRODUCT MANAGEMENT ===
        Route::get('add_product', 'add_product');
        Route::post('upload_product', 'upload_product');
        Route::get('view_product', 'view_product');
        Route::get('update_product/{id}', 'update_product');
        Route::post('edit_product/{id}', 'edit_product');
        Route::get('product_search', 'product_search');
        Route::get('delete_product/{id}', 'delete_product'); // Soft Delete biasa

        // === TRASH & RESTORE SYSTEM (SEKARANG SUDAH AMAN) ===
        // Sebelumnya ini di luar middleware, sangat berbahaya. Sekarang aman.
        Route::get('/trashed_products', 'trashed_products')->name('trashed_products');
        Route::get('/restore_product/{id}', 'restore_product')->name('restore_product');
        Route::get('/force_delete_product/{id}', 'force_delete_product')->name('force_delete_product');

        // === ORDER MANAGEMENT ===
        Route::get('view_orders', 'view_order');
        Route::get('on_the_way/{id}', 'on_the_way');
        Route::get('delivered/{id}', 'delivered');
    });
});

// --- OPTIONAL / ADVANCED PERMISSIONS (Spatie) ---
// Bagian ini disimpan jika Anda menggunakan package spatie/laravel-permission
Route::middleware(['auth', 'permission:create posts'])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
});

require __DIR__.'/auth.php';