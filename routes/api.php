<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

//LOGIN
Route::post('/login', [ApiController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    //CATEGORY
    Route::get('/category', [ApiController::class, 'category']);

    //SEARCH
    Route::get('/search', [ApiController::class, 'search']);

    //PRODUCT
    Route::get('/product/{id}/show', [ApiController::class, 'showproduct']);

    //CART
    Route::get('/cart', [ApiController::class, 'cart']);
    Route::post('/postcart', [ApiController::class, 'postcart']);
    Route::delete('/cart/{id}/delete', [ApiController::class, 'removecart']);
    Route::post('/postorder', [ApiController::class, 'postorder']);

    //ORDER
    Route::get('/order', [ApiController::class, 'order']);
    Route::post('/postonline', [ApiController::class, 'postonline']);
    Route::post('/postcash', [ApiController::class, 'postcash']);
    Route::get('/order/{id}/show', [ApiController::class, 'showorder']);
    Route::post('/order/{id}/archive', [ApiController::class, 'archive']);
    Route::delete('/order/{id}/delete', [ApiController::class, 'destroy']);

    //SETTLEMENT
    Route::get('/settlement', [ApiController::class, 'settlement']);
    Route::post('/poststart', [ApiController::class, 'poststart']);

    //HISTORY
    Route::get('/history', [ApiController::class, 'history']);

    //LOGOUT
    Route::post('/logout', [ApiController::class, 'logout']);
});
