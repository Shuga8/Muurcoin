<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoinsController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\WithdrawalRequestsController;

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
        // return response()->json(['token' => $token, 'email' => $email]);
        return redirect("https://nationalex.org/reset-password?token=$token&email=$email");
    })->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


Route::middleware('auth:sanctum', 'verified', 'status')->group(function () {
    Route::name('api.')->group(function () {
        Route::resource('/user', UserController::class);
        Route::resource('/transactions', TransactionsController::class);
        Route::resource('/coins', CoinsController::class);
    });

    Route::prefix('exchange')->name('api.exchange.')->group(function () {
        Route::post('/', [ExchangeController::class,  'store'])->name('store');
    });
    Route::prefix('/crypto')->name('api.crypto.')->group(function () {
        Route::get('/', [CryptoController::class, 'index'])->name('index');
        Route::get('/coinmarketcap', [CryptoController::class, 'coinmarket'])->name('coinmarket');
    });
    Route::prefix('/deposit')->name('api.deposit.')->group(function () {
        Route::post('/', [DepositController::class, 'achieve'])->name('achieve');
    });

    Route::prefix('transfer')->name('api.transfer.')->group(function () {
        Route::post('/achieve', [TransferController::class, 'achieve'])->name('achieve');
    });

    Route::prefix('withdrawal')->name('api.withdrawal.')->group(function () {
        Route::get('all', [WithdrawalRequestsController::class, 'all'])->name('all');
        Route::post('/place', [WithdrawalRequestsController::class, 'place'])->name('place');
    });
});
