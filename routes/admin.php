<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->name('admin.')->group(function () {

    Route::controller('AuthController')->prefix('auth')->group(function () {
        Route::get('/', 'index')->name('login');
    });

    Route::middleware('admin')->group(function () {
        Route::controller('AdminController')->group(function () {
            Route::get('dashboard', 'index')->name('dashboard');
        });
    });
});
