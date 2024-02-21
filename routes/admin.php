<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->prefix('admin')->group(function () {

    Route::controller('AuthController')->middleware('admin.guest')->prefix('auth')->group(function () {
        Route::get('');
    });
});
