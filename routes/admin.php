<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->name('admin.')->group(function () {

    Route::controller('AuthController')->middleware('admin.guest')->prefix('auth')->group(function () {
        Route::get('/', 'index')->name('login');
        Route::post('/', 'auth')->name('login.auth');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::middleware('admin')->group(function () {
        Route::controller('AdminController')->group(function () {
            Route::get('dashboard', 'index')->name('dashboard');
        });
    });
});
