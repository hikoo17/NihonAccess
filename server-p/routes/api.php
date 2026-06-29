<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    // Route Authentication
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/logout', 'logout')->middleware('auth:sanctum');
});

Route::post('/register-course', [MidtransController::class, 'registerCourse']);
Route::get('/registration/check-status/{order_id}', [MidtransController::class, 'checkStatus']);
Route::post('/registration/sync-payment/{order_id}', [MidtransController::class, 'syncPaymentStatus']);
Route::post('/registration/confirm-snap', [MidtransController::class, 'confirmSnapPayment']);
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

Route::middleware('auth:sanctum')->group(function () {
    // Route Master Data
    Route::get('/currencies', [CurrencyController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);

    // Route Wallets
    Route::apiResource('/wallets', WalletController::class);

    // Route Transactions
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
    Route::get('/transactions', [TransactionController::class, 'index']);

    // Route Summary 
    Route::get('/reports/summary-by-category/{type}', [ReportController::class, 'index']);
});
