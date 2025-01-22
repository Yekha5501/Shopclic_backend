<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\StatisticsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/statistics/daily', [StatisticsController::class, 'dailyStats']);






Route::middleware('auth:sanctum')->group(function () {

    Route::get('/statistics/daily', [StatisticsController::class, 'dailyStats']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/sales/daily-summary', [TransactionController::class, 'dailySalesSummary']);
    Route::post('/broadcast', [ProductController::class, 'broadcastMessage']);
    Route::get('/products', [ProductController::class, 'index']);
});


Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
