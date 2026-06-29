<?php

use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\MidtransController;
use Illuminate\Support\Facades\Route;

Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{slug}', [PackageController::class, 'show']);

Route::post('/register-course', [MidtransController::class, 'registerCourse']);
Route::get('/registration/check-status/{order_id}', [MidtransController::class, 'checkStatus']);
Route::post('/registration/sync-payment/{order_id}', [MidtransController::class, 'syncPaymentStatus']);
Route::post('/registration/confirm-snap', [MidtransController::class, 'confirmSnapPayment']);
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
