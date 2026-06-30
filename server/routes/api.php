<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\Teacher\TeacherCharacterController;
use App\Http\Controllers\Api\Teacher\TeacherCourseController;
use App\Http\Controllers\Api\Teacher\TeacherDashboardController;
use App\Http\Controllers\Api\Teacher\TeacherLessonController;
use App\Http\Controllers\Api\Teacher\TeacherListeningController;
use App\Http\Controllers\Api\Teacher\TeacherQuizController;
use App\Http\Controllers\Api\Teacher\TeacherWritingController;
use Illuminate\Support\Facades\Route;

Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{slug}', [PackageController::class, 'show']);

Route::post('/register-course', [MidtransController::class, 'registerCourse']);
Route::get('/registration/check-status/{order_id}', [MidtransController::class, 'checkStatus']);
Route::post('/registration/sync-payment/{order_id}', [MidtransController::class, 'syncPaymentStatus']);
Route::post('/registration/confirm-snap', [MidtransController::class, 'confirmSnapPayment']);
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

    Route::prefix('teacher')->group(function () {
        Route::get('dashboard', [TeacherDashboardController::class, 'index']);

        Route::apiResource('courses', TeacherCourseController::class)->only(['index', 'show']);

        Route::apiResource('lessons', TeacherLessonController::class)->only(['store', 'show', 'update', 'destroy']);

        Route::apiResource('quizzes', TeacherQuizController::class);

        Route::apiResource('character-exercises', TeacherCharacterController::class);

        Route::apiResource('listening-exercises', TeacherListeningController::class);

        Route::apiResource('writing-exercises', TeacherWritingController::class);
    });
});

