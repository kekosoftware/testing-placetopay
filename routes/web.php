<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Initial View Controller
 */
Route::get('/',
    [ProductController::class, 'index']
)->name('home');

/**
 * Orders
 */
Route::get('/checkout/{id}',
	[ProductController::class, 'formCheckout']
)->name('checkout');

Route::get('/show',
	[OrderController::class, 'show']
)->name('show');


Route::resource('/order', OrderController::class);
