<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('home');
});

// Product routes
Route::get('/products', function () {
    return view('products.index');
});

Route::get('/products/category/{slug}', function ($slug) {
    return view('products.category', ['slug' => $slug]);
});

Route::get('/products/{slug}', function ($slug) {
    return view('products.show', ['slug' => $slug]);
});

// Admin routes
Route::prefix('admin')->group(function () {
    // Admin auth routes
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('admin.login');
    
    // Protected admin routes
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        // Products management
        Route::get('/products', function () {
            return view('admin.products.index');
        })->name('admin.products.index');
        
        Route::get('/products/create', function () {
            return view('admin.products.create');
        })->name('admin.products.create');
        
        Route::get('/products/{id}/edit', function ($id) {
            return view('admin.products.edit', ['id' => $id]);
        })->name('admin.products.edit');
        
        // Categories management
        Route::get('/categories', function () {
            return view('admin.categories.index');
        })->name('admin.categories.index');
        
        Route::get('/categories/create', function () {
            return view('admin.categories.create');
        })->name('admin.categories.create');
        
        Route::get('/categories/{id}/edit', function ($id) {
            return view('admin.categories.edit', ['id' => $id]);
        })->name('admin.categories.edit');
    });
});