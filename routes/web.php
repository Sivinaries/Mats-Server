<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Pagescontroller;
use App\Http\Controllers\HistoyController;
use App\Http\Controllers\InventController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettlementController;


//AUTH CONTROLLER
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signin', [AuthController::class, 'signin'])->name('signin');

Route::middleware('auth:sanctum')->group(function () {

    //ADMIN

    //PAGES CONTROLLER
    Route::get('/dashboard', [Pagescontroller::class, 'dashboard'])->name('dashboard');
    Route::get('/search', [Pagescontroller::class, 'search'])->name('search');

    //QR CONTROLLER
    Route::get('/scanner', [QrController::class, 'index'])->name('scanner');
    Route::get('/product/{id}/qr', [QrController::class, 'show'])->name('showqr');

    //USER CONTROLLER
    Route::get('/users', [UserController::class, 'index'])->name('user');
    Route::get('/createuser', [UserController::class, 'create'])->name('adduser');
    Route::post('/postuser', [UserController::class, 'store'])->name('postuser');
    Route::delete('/user/{id}/delete', [UserController::class, 'destroy'])->name('deluser');

    //ORDER CONTROLLER
    Route::get('/order', [OrderController::class, 'index'])->name('order');
    Route::get('/createorder', [OrderController::class, 'create'])->name('addorder');
    Route::post('/postorder', [OrderController::class, 'store'])->name('postorder');
    Route::delete('/order/{id}/delete', [OrderController::class, 'destroy'])->name('delorder');
    Route::post('/order/{id}/archive', [OrderController::class, 'archive'])->name('archive');
    Route::post('/cashpayment', [OrderController::class, 'cashpayment'])->name('cashpayment');
    Route::post('/onlinepayment', [OrderController::class, 'onlinepayment'])->name('onlinepayment');

    //MENU CONTROLLER
    Route::get('/product', [ProductController::class, 'index'])->name('product');
    Route::get('/createproduct', [ProductController::class, 'create'])->name('addproduct');
    Route::post('/postproduct', [ProductController::class, 'store'])->name('postproduct');
    Route::get('/editproduct/{id}', [ProductController::class, 'edit'])->name('editproduct');
    Route::get('/product/{id}/show', [ProductController::class, 'show'])->name('showproduct');
    Route::put('/product/{id}/update', [ProductController::class, 'update'])->name('updateproduct');
    Route::delete('/product/{id}/delete', [ProductController::class, 'destroy'])->name('delproduct');

    //CATEGORY CONTROLLER
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/addcategory', [CategoryController::class, 'create'])->name('addcategory');
    Route::post('/postcategory', [CategoryController::class, 'store'])->name('postcategory');
    Route::get('/editcategory/{id}', [CategoryController::class, 'edit'])->name('editcategory');
    Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('updatecategory');
    Route::delete('/category/{id}/delete', [CategoryController::class, 'destroy'])->name('delcategory');

    //HISTORY CONTROLLER
    Route::get('/history', [Histoycontroller::class, 'index'])->name('history');
    Route::get('/export-orders', [HistoyController::class, 'exportOrders'])->name('exportOrders');

    //CART  CONTROLLER
    Route::get('/cart', [Cartcontroller::class, 'index'])->name('addcart');
    Route::post('/postcart', [CartController::class, 'store'])->name('postcart');
    Route::delete('/cart/{id}/delete', [CartController::class, 'destroy'])->name('removecart');

    //SIZE CONTROLLER
    Route::get('/inventory', [InventController::class, 'index'])->name('inventory');

    //SIZE CONTROLLER
    Route::get('/size', [SizeController::class, 'index'])->name('size');
    Route::get('/addsize', [SizeController::class, 'create'])->name('addsize');
    Route::post('/postsize', [SizeController::class, 'store'])->name('postsize');
    Route::get('/editsize/{id}', [SizeController::class, 'edit'])->name('editsize');
    Route::put('/size/{id}/update', [SizeController::class, 'update'])->name('updatesize');
    Route::delete('/size/{id}/delete', [SizeController::class, 'destroy'])->name('delsize');

    //EXPENSE CONTROLLER
    Route::get('/expense', [ExpenseController::class, 'index'])->name('expense');
    Route::get('/addexpense', [ExpenseController::class, 'create'])->name('addexpense');
    Route::post('/postexpense', [ExpenseController::class, 'store'])->name('postexpense');
    Route::get('/editexpense/{id}', [ExpenseController::class, 'edit'])->name('editexpense');
    Route::put('/expense/{id}/update', [ExpenseController::class, 'update'])->name('updateexpense');
    Route::delete('/expense/{id}/delete', [ExpenseController::class, 'destroy'])->name('delexpense');

    //SETTLEMENT CONTROLLER
    Route::get('/settlement', [SettlementController::class, 'index'])->name('settlement');
    Route::get('/settlement/{id}/show', [SettlementController::class, 'show'])->name('showsettlement');
    Route::delete('/settlement/{id}/delete', [SettlementController::class, 'destroy'])->name('delsettlement');
    Route::get('/addstartamount', [SettlementController::class, 'startamount'])->name('addstartamount');
    Route::get('/addtotalamount', [SettlementController::class, 'totalamount'])->name('addtotalamount');
    Route::post('/createstart', [SettlementController::class, 'poststart'])->name('poststart');
    Route::post('/createtotal', [SettlementController::class, 'posttotal'])->name('posttotal');

    //LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});
