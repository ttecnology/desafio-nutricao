<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'apiDetails']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::resource('products', ProductController::class);


