<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\CategoryController as PublicCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Public product routes
Route::get('/products', [PublicProductController::class, 'index']);
Route::get('/products/featured', [PublicProductController::class, 'getFeaturedProducts']);
Route::get('/products/{product}', [PublicProductController::class, 'show']);
Route::get('/products/category/{category}', [PublicProductController::class, 'getProductsByCategory']);

// Public category routes
Route::get('/categories', [PublicCategoryController::class, 'getActiveCategories']);
Route::get('/categories/{category}', [PublicCategoryController::class, 'show']);

// Protected routes (admin only)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin auth routes
    Route::post('/admin/register', [AdminAuthController::class, 'register']);
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin/user', [AdminAuthController::class, 'user']);
    Route::post('/admin/refresh', [AdminAuthController::class, 'refresh']);
    
    // Admin product routes
    Route::get('/admin/products', [ProductController::class, 'index']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::get('/admin/products/{id}', [ProductController::class, 'show']);
    Route::put('/admin/products/{id}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);
    Route::put('/admin/products/{id}/toggle-status', [ProductController::class, 'toggleStatus']);
    Route::put('/admin/products/{id}/toggle-featured', [ProductController::class, 'toggleFeatured']);
    Route::post('/admin/products/bulk-action', [ProductController::class, 'bulkAction']);
    Route::get('/admin/products/count', [ProductController::class, 'getProductCount']);
    
    // Admin product image routes
    Route::put('/admin/product-images/{id}/set-primary', [ProductImageController::class, 'setPrimary']);
    Route::delete('/admin/product-images/{id}', [ProductImageController::class, 'destroy']);
    Route::post('/admin/product-images/update-order', [ProductImageController::class, 'updateOrder']);
    
    // Admin category routes
    Route::post('/admin/categories', [CategoryController::class, 'store']);
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy']);
    Route::put('/admin/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive']);
});