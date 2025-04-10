<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'getIphones']);
Route::get('/products/fetch', [ProductController::class, 'fetchIphones']);
Route::post('/products', [ProductController::class, 'addProduct']);

Route::get('/test-api', function() {
    return Http::withoutVerifying()
        ->get('https://dummyjson.com/products/category/smartphones')
        ->json();
});
