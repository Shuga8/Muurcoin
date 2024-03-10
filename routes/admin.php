<?php


use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->name('admin.')->group(function () {

    Route::controller('AuthController')->prefix('auth')->group(function () {
        Route::get('login', 'index')->name('login')->middleware('admin.guest');
        Route::post('/', 'auth')->name('login.auth')->middleware('admin.guest');
        // Logout function
        Route::get('logout', 'logout')->name('logout')->middleware('admin');
    });

    Route::middleware('admin')->group(function () {

        Route::controller('AdminController')->group(function () {
            Route::get('dashboard', 'index')->name('dashboard');
            Route::get('create', 'create')->name('add-admin');
            Route::post('store', 'store')->name('store');
            Route::get('password', 'password')->name('password');
            Route::post('password', 'passwordUpdate')->name('password.update');
        });

        Route::controller('ManageUsersController')->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'allUsers')->name('all');
            Route::get('/active', 'activeUsers')->name('active');
            Route::get('/unverified-email', 'unverifiedEmailUsers')->name('email.unverified');
            Route::get('/verified-email', 'verifiedEmailUsers')->name('email.verified');
            Route::get('/banned', 'bannedUsers')->name('banned');

            Route::get('detail/{id}', 'detail')->name('detail');

            Route::post('status/{id}', 'status')->name('status');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
        });

        Route::controller('ReportController')->name('report.')->prefix('report')->group(function () {
            Route::get('transactions/all', 'all')->name('all');
            Route::get('transaction/{id}', 'transaction')->name('transaction');
        });

        Route::controller('ExchangeController')->name('exchange.')->prefix('exchange')->group(function () {
            Route::get('', 'index')->name('all');
        });

        Route::controller('DepositController')->name('deposit.')->prefix('deposit')->group(function () {
            Route::get('', 'index')->name('all');
            Route::get('details/{id}', 'details')->name('list');
        });

        Route::controller('WithdrawalController')->name('withdraw.')->prefix('withdraw')->group(function () {
            Route::get('log/{id}', 'log')->name('log');
            Route::get('requests/{user}', 'quests')->name('requests');
            Route::put('status-update/{id}', 'update')->name('update');
        });
    });
});
