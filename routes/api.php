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

// http://localhost:8000/api/equity/callback
//jenga webhook
Route::any('/equity/callback', [App\Http\Controllers\TransactionController::class, 'webhook'])->name('equity.callback');

//callback
Route::any('/callback', [App\Http\Controllers\TransactionController::class, 'callback'])->name('callback');



https://e8f1-41-90-69-253.eu.ngrok.io/api/equity/callback