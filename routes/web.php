<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Routes pour les produits
Route::resource('products', ProductController::class);

// Routes pour les catégories
Route::resource('categories', CategoryController::class);

// Routes pour les fournisseurs
Route::resource('suppliers', SupplierController::class);

// Routes pour les mouvements de stock
Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store']);

// Authentification (si nécessaire)

