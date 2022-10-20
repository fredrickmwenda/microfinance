<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// mpesa/b2c/timeout
Route::any('/mpesa/b2c/timeout', [App\Http\Controllers\DisburseController::class, 'queueTimeOutURL'])->name('mpesa.b2c.timeout');
// mpesa/b2c/result
Route::any('/mpesa/b2c/result', [App\Http\Controllers\DisburseController::class, 'transactionStatusQueryURL'])->name('mpesa.b2c.result');
// enrollment_callback', [App\Http\Controllers\HomeController::class, 'enroll_callback'])->name('homeController');

//jenga webhook
Route::any('/jenga/webhook', [App\Http\Controllers\TransactionController::class, 'webhook'])->name('jenga.webhook');

