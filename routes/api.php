<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\APICategoryController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\Payment\TransactionController;
use App\Http\Controllers\Payment\TripayCallbackController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;

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

//Authenticate JWT
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');
Route::post('/logout', LogoutController::class)->name('logout');


// Product
Route::resource('product', APIController::class);

// Schedule
Route::resource('schedule', ScheduleController::class);

// order
// Route::resource('order', APIController::class);
Route::post('order', [OrderController::class, 'store'])->name('order.store');


//Category
Route::resource('category', APICategoryController::class);

// API Payment
Route::get('/item/{id}/checkout', [App\Http\Controllers\APIController::class, 'checkout'])->name('checkout');
Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
Route::get('/transaction/{reference}', [TransactionController::class, 'show'])->name('transaction.show');
Route::post('/callback', [TripayCallbackController::class, 'handle'])->name('callback');
Route::get('/transaction-detail', [TransactionController::class, 'history'])->name('transaction');
