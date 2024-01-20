<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoinsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */

/* Contain auth methods like [login, register, logout, verification email, passwords reset] */

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    Route::get('/email/verify-notice', [AuthController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [AuthController::class, 'resend'])->name('verification.resend');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::get('/reset-password/{token}', function (Request $request, string $token) {
        $email = $request->query('email');
        return response()->json(['token' => $token, 'email' => $email]);
    })->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::resource('/transactions', TransactionsController::class);
    Route::resource('/coins', CoinsController::class);

    Route::controller('ExchangeCryptoController')->prefix('exchange')->name('api.exchange.')->group(function () {
        Route::post('/', 'store')->name('store');
    });
});
