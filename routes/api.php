<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/login', [ApiController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/category', [ApiController::class, 'category']);
    Route::get('/product/{id}', [ApiController::class, 'showproduct']);
    Route::post('/postcart', [ApiController::class, 'postcart'])->name('postcart');
    Route::delete('/cart/{id}/delete', [ApiController::class, 'removecart']);
    Route::post('/postorder', [ApiController::class, 'postorder'])->name('postorder');
    Route::get('/cart', [ApiController::class, 'cart']);
    Route::get('/order', [ApiController::class, 'order']);
    Route::get('/history', [ApiController::class, 'history']);
    Route::get('/settlement', [ApiController::class, 'settlement']);
    Route::post('/logout', [ApiController::class, 'logout']);
    
});
